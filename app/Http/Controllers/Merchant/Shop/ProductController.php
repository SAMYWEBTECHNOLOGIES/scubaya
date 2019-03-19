<?php

namespace App\Http\Controllers\Merchant\Shop;

use App\Scubaya\Helpers\CompressImage;
use App\Scubaya\model\Hotel;
use App\Scubaya\model\ManageDiveCenter;
use App\Scubaya\model\MerchantUsersRoles;
use App\Scubaya\model\ProductCategories;
use App\Scubaya\model\Products;
use App\Scubaya\model\Shops;
use App\Scubaya\model\TaxRate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;

class ProductController extends Controller
{
    private $authUserId ;

    private $noOfProductsPerPage   =   15;

    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware(function ($request, $next) {
            if(Auth::user()->is_merchant_user) {
                $this->authUserId   =   MerchantUsersRoles::where('user_id', Auth::id())->value('merchant_id');
            } else {
                $this->authUserId   =   Auth::id();
            }

            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $products   =   Products::where('merchant_key', $this->authUserId)
                                ->where('shop_id', $request->shop_id)
                                ->paginate($this->noOfProductsPerPage);

        $sno        =   (($products->currentPage() - 1) * $this->noOfProductsPerPage) + 1;

        return view('merchant.shop.products.index')
                        ->with('products', $products)
                        ->with('shopId', $request->shop_id)
                        ->with('sno', $sno);
    }

    protected function _prepareData($request, $file)
    {
        $product    =   new \stdClass();

        $product->title             =   $request->get('product_title');
        $product->sku               =   $request->get('product_sku');
        $product->weight            =   $request->get('product_weight');
        $product->product_status    =   $request->get('product_status');
        $product->tax               =   $request->get('product_tax');
        $product->manufacturer      =   $request->get('product_manufacturer');
        $product->color             =   $request->get('product_color');
        $product->availability_from =   $request->get('product_available_from');
        $product->availability_to   =   $request->get('product_available_till');
        $product->price             =   $request->get('product_price');
        $product->incl_in_course    =   $request->get('product_included_in_course');
        $product->no_of_products    =   $request->get('no_of_products_available');
        $product->product_type      =   $request->get('product_type');
        $product->category          =   $request->get('product_category');
        $product->sub_accounts      =   $this->_formatSubAccounts($request->get('sub_accounts'));
        $product->short_description =   $request->get('product_short_description');
        $product->description       =   $request->get('product_description');

        if($file){
            $product->product_image =   $file->getClientOriginalName();
        }

        return $product;
    }

    public function save(Request $request)
    {
        if($request->isMethod('post')){

            $this->validate($request, [
                'product_title' =>  'required',
                'product_price' =>  'required|numeric',
                'product_image' =>  'required|image|mimes:jpg,png,jpeg,gif,svg,bmp|max:2048',
            ]);

            $file       =   $request->file('product_image');

            $product    =   $this->_prepareData($request, $file);

            $product->merchant_key      =   $this->authUserId;
            $product->shop_id           =   $request->shop_id;

            $product    =   Products::saveProduct($product);

            // save product image
            $this->_saveImageInLocalDirectory($file, $product);

            return Redirect::to(route('scubaya::merchant::shop::products', [Auth::id(), $request->shop_id]));
        }

        // prepare tax class for the country in which shop is located
        $shopCountry        =   json_decode(Shops::where('id', $request->shop_id)->value('country'));

        return view('merchant.shop.products.create')
                ->with('shopId', $request->shop_id)
                ->with('taxClass', $this->_getTaxClasses($shopCountry))
                ->with('subAccounts', $this->_getSubAccounts())
                ->with('authId', $this->authUserId);
    }

    public function update(Request $request)
    {
        if($request->isMethod('post')){

            $this->validate($request, [
                'product_title'                 =>  'required',
                'product_price'                 =>  'required',
                'product_image'                 =>  'sometimes|required|image|mimes:jpg,png,jpeg,gif,svg,bmp|max:2048',
            ]);

            $file       =   $request->file('product_image');

            $product    =   Products::updateProduct($request->product_id, $this->_prepareData($request, $file));

            // save product image
            if($file){
                $this->_removeImageFromLocalDirectory($product);
                $this->_saveImageInLocalDirectory($file, $product);
            }

            return Redirect::to(route('scubaya::merchant::shop::products', [Auth::id(), $request->shop_id]));
        }

        $product    =   Products::find($request->product_id);

        // prepare tax class for the country in which shop is located
        $shopCountry    =   json_decode(Shops::where('id', $request->shop_id)->value('country'));

        return view('merchant.shop.products.edit')
                ->with('product', $product)
                ->with('shopId', $request->shop_id)
                ->with('authId', $this->authUserId)
                ->with('subAccounts', $this->_getSubAccounts())
                ->with('taxClass', $this->_getTaxClasses($shopCountry));
    }

    public function delete(Request $request)
    {
        $product    =   Products::find($request->product_id);

        $this->_removeImageFromLocalDirectory($product);

        Products::destroy($request->product_id);

        return Redirect::to(route('scubaya::merchant::shop::products', [Auth::id(), $request->shop_id]));
    }

    public function isIncludedInCourse(Request $request)
    {
        $product   =   Products::findOrFail($request->pId);

        $product->incl_in_course   =  $request->isIncl;
        $product->update();
    }

    protected function _getTaxClasses($shopCountry)
    {
        $i          =   0;
        $taxClass   =   array();

        $taxRates   =   TaxRate::where('merchant_key', $this->authUserId)->get();

        if(count($taxRates)) {
            foreach ($taxRates as $rate) {
                $country    =   json_decode($rate->country);

                if(strtolower($country->iso_code2) == strtolower($shopCountry->iso_code2)) {
                    $taxClass[$i]['country']    =   $country->name;
                    $taxClass[$i]['rate']       =   $rate->rate;
                }

                $i++;
            }
        }

        return count($taxClass) ? $taxClass : null;
    }

    /* To format sub accounts */
    protected function _formatSubAccounts($accountRights)
    {
        $AccountRights  =   array();

        if(count($accountRights)) {
            foreach ($accountRights as $right) {
                list($type, $id)   =   explode('.', $right);

                $AccountRights[$type][] = (int)$id;
            }
        }

        return json_encode($AccountRights);
    }

    /* To get all sub accounts */
    protected function _getSubAccounts()
    {
        /*$shops    =   Shops::join('website_details', 'shop_information.id', '=', 'website_details.website_id')
                            ->join('website_details_x_documents as doc','website_details.id','doc.website_detail_id')
                            ->where('doc.status', MERCHANT_STATUS_APPROVED)
                            ->where('website_details.website_type', SHOP)
                            ->where('shop_information.merchant_key', $this->authUserId)
                            ->select('shop_information.*')
                            ->get();*/


        $shops      =   Shops::where('merchant_key', $this->authUserId)
                            ->where('status', PUBLISHED)
                            ->get();

        /*$hotels   =   Hotel::join('website_details', 'hotels_general_information.id', '=', 'website_details.website_id')
                            ->join('website_details_x_documents as doc','website_details.id','doc.website_detail_id')
                            ->where('doc.status', MERCHANT_STATUS_APPROVED)
                            ->where('website_details.website_type', HOTEL)
                            ->where('hotels_general_information.merchant_primary_id', $this->authUserId)
                            ->select('hotels_general_information.*')
                            ->get();*/

        $hotels   =   Hotel::where('merchant_primary_id', $this->authUserId)
                            ->where('status', PUBLISHED)
                            ->get();

        /*$centers  =   ManageDiveCenter::join('website_details', 'manage_dive_centers.id', '=', 'website_details.website_id')
                                        ->join('website_details_x_documents as doc','website_details.id','doc.website_detail_id')
                                        ->where('doc.status', MERCHANT_STATUS_APPROVED)
                                        ->where('website_details.website_type', DIVE_CENTER)
                                        ->where('manage_dive_centers.merchant_key', $this->authUserId)
                                        ->select('manage_dive_centers.*')
                                        ->get();*/

        $centers    =   ManageDiveCenter::where('merchant_key', $this->authUserId)
                                        ->where('status', PUBLISHED)
                                        ->get();

        return  [
            'shop'      =>  $shops,
            'hotel'     =>  $hotels,
            'centers'   =>  $centers
        ];
    }

    /* save room profile image in local directory */
    protected function _saveImageInLocalDirectory($file, $product)
    {
        $path     =   public_path(). '/assets/images/scubaya/shop/products/'.$product->merchant_key.'/';
        File::makeDirectory($path, 0777, true, true);

        $filename   =   $product->id.'-'.$product->product_image;
        $compressImage = new CompressImage();
        $compressImage->compressImage($file,$path,$filename);
    }

    /* delete image form directory */
    protected function _removeImageFromLocalDirectory($product)
    {
        $path       =   public_path(). '/assets/images/scubaya/shop/products/'.$product->merchant_key.'/'.$product->id.'*';
        $log_files  =   File::glob($path);
        File::delete($log_files);
    }
}
