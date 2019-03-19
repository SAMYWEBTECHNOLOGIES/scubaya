<?php

namespace App\Http\Controllers\Merchant\Shop;

use App\Scubaya\model\MerchantUsersRoles;
use App\Scubaya\model\Products;
use App\Scubaya\model\Shops;
use App\Scubaya\model\WebsiteDetails;
use App\Scubaya\model\WebsiteDocumentsMapper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ShopController extends Controller
{
    private $authUserId ;

    private $noOfShopsPerPage   =   15;

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

    public function index()
    {
        if(Auth::user()->is_merchant_user) {
            $shops        =   array();
            $shopRights   =   json_decode(MerchantUsersRoles::where('user_id', Auth::id())->value('sub_account_rights'));

            if($shopRights) {
                foreach ($shopRights as $key => $value) {
                    if($key == 'shop') {
                        array_push($shops, $value);
                    }
                }
            }

            $Shops    =   Shops::whereIn('id', array_flatten($shops))->paginate($this->noOfShopsPerPage);
        } else {
            $Shops    =   Shops::where('merchant_key', $this->authUserId)->paginate($this->noOfShopsPerPage);
        }

        return view('merchant.shop.index')
            ->with('shops', $Shops)
            ->with('auth_id', $this->authUserId);
    }

    protected function _prepareShop($request, $file)
    {
        $shop   =   new \stdClass();

        $shop->merchant_key     =   $this->authUserId;
        $shop->name             =   $request->get('name');

        if($file) {
            $shop->profile_image    =   $file->getClientOriginalName();
        }

        $shop->address          =   $request->get('address');
        $shop->city             =   $request->get('city');
        $shop->state            =   $request->get('state');

        $shop->country          =   json_encode([
            'name'      =>  $request->get('country'),
            'iso_code2' =>  $request->get('country_code')
        ]);

        $shop->zipcode          =   $request->get('zip_code');
        $shop->latitude         =   $request->get('latitude');
        $shop->longitude        =   $request->get('longitude');
        $shop->status           =   PUBLISHED;

        return $shop;
    }

    public function create(Request $request)
    {
        if($request->isMethod('post')) {

            $validator = Validator::make($request->all(), [
                'name'      =>  'required',
//                'image'     =>  'bail|required|image|mimes:jpg,png,jpeg,gif,svg,bmp|max:2048|dimensions:max_width=357,max_height=238',
                'address'   =>  'required'
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator, 'shopError')
                    ->withInput();
            }

            // save shop
            $file   =   $request->file('image');
            $shop   =   Shops::saveShop($this->_prepareShop($request, $file));

            /*
             * if user has role like manager , admin, financier etc
             * and they login in merchant section & create shop, dive center,
             * hotel then update their access rights
             */
            if(Auth::user()->is_merchant_user) {
                MerchantUsersRoles::updateSubAccountRights($shop->id, 'shop');
            }

            // save shop image
            if($file) {
                $this->_storeImageInLocalDirectory($file, $shop);
            }

            return Redirect::to(route('scubaya::merchant::shop::shops', [Auth::id()]));

            /*return redirect()->back()->withInput()
                             ->with(['shop' => $shop, 'show_popup' => true]);*/
        }

        return view('merchant.shop.create');
    }

    protected function _updateSubAccountAccessRights($shopId)
    {
        $rights   =   (array)json_decode(MerchantUsersRoles::where('user_id', Auth::id())->value('sub_account_rights'));

        if(count($rights)) {
            if(array_key_exists('shop', $rights)) {
                if(! in_array($shopId, $rights['shop']) ) {
                    $oldRights         =   $rights['shop'];
                    array_push($oldRights, $shopId);

                    $rights['shop']    =   $oldRights;
                }
            } else {
                $rights['shop'][]      =   $shopId;
            }

            MerchantUsersRoles::where('user_id', Auth::id())->update([
                'sub_account_rights'    =>  json_encode($rights)
            ]);
        }
    }

    public function update(Request $request)
    {
        if($request->isMethod('post')) {
            $this->validate($request, [
                'name'      =>  'required',
                'image'     =>  'sometimes|bail|required|image|mimes:jpg,png,jpeg,gif,svg,bmp|max:2048|dimensions:max_width=357,max_height=238',
                'address'   =>  'required'
            ]);

            $file   =   $request->file('image');
            $shop   =   Shops::updateShop($request->shop_id, $this->_prepareShop($request, $file));

            //remove existing image of hotel and save new one
            if($file){
                $this->_removeImageInLocalDirectory($shop);
                $this->_storeImageInLocalDirectory($file, $shop);
            }

            return Redirect::to(route('scubaya::merchant::shop::shops', [Auth::id()]));
        }

        $shop   =   Shops::find($request->shop_id);

        return view('merchant.shop.edit')->with('shop', $shop);
    }

    public function delete(Request $request)
    {
        $shop   =   Shops::find($request->shop_id);

        // delete image form directory
        $this->_removeImageInLocalDirectory($shop);

        // destroy shop information
        Shops::destroy($request->shop_id);

        // delete products
        Products::where('merchant_key', $this->authUserId)
            ->where('shop_id', $request->shop_id)->delete();

        // delete courses
        Courses::where('merchant_key', $this->authUserId)
                ->where('shop_id', $request->shop_id)->delete();

        // destroy website details regarding to particular shop
        /*WebsiteDetails::destroy($request->detail_id);*/

        // delete all docs related to details of website
        /*WebsiteDocumentsMapper::where('website_detail_id', $request->detail_id)->delete();*/

        if(Auth::user()->is_merchant_user) {
            MerchantUsersRoles::deleteSubAccountRights($request->shop_id, 'shop');
        }

        return Redirect::to(route('scubaya::merchant::shop::shops', [Auth::id()]));
    }

    /*
     * store shop image in local directory
    */
    protected function _storeImageInLocalDirectory($file, $shop)
    {
        $path     =   public_path(). '/assets/images/scubaya/shop/'.$shop->merchant_key.'/';
        File::makeDirectory($path, 0777, true, true);

        $filename = $file->getClientOriginalName();
        $file->move($path, ($shop->id.'-'.$filename));
    }

    /* delete shop image form directory */
    protected function _removeImageInLocalDirectory($shop)
    {
        $path       =   public_path(). '/assets/images/scubaya/shop/'.$shop->merchant_key.'/'.$shop->id.'*';
        $log_files  =   File::glob($path);
        File::delete($log_files);
    }
}
