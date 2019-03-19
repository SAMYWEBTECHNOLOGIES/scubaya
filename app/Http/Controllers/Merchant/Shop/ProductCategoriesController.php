<?php

namespace App\Http\Controllers\Merchant\Shop;

use App\Scubaya\model\MerchantUsersRoles;
use App\Scubaya\model\ProductCategories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ProductCategoriesController extends Controller
{
    private $authUserId ;

    private $noOfCategoriesPerPage   =   15;

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
        $productCategories      =    ProductCategories::where('merchant_key', $this->authUserId)
                                                        ->paginate($this->noOfCategoriesPerPage);

        $sno    =    (($productCategories->CurrentPage() - 1) * $this->noOfCategoriesPerPage) + 1;

        return view('merchant/shop/productCategories/index',[
            'sno'               =>  $sno,
            'productCategories' =>  $productCategories,
            'authId'            =>  $this->authUserId
        ]);
    }

    protected function _prepareData($request)
    {
        $data   =   [
            'active'                =>  1,
            'name'                  =>  $request->category_name,
            'parent_id'             =>  $request->category_group,
            'merchant_key'          =>  $this->authUserId
        ];

        return $data;
    }

    public function addProductCategories(Request $request)
    {
        $data = $this->_prepareData($request);

        ProductCategories::addProductCategories($data);

        $request->session()->flash('success','Product Categories added successfully.');

        return redirect()->route('scubaya::merchant::shop::product_categories',Auth::id());
    }
}
