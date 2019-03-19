<?php

namespace App\Http\Controllers\Front;

use App\Scubaya\model\Cart;
use App\Scubaya\model\DiveCenterCheckout;
use App\Scubaya\model\ProductCheckouts;
use App\Scubaya\model\Products;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Input;

class ProductCheckoutController extends Controller
{
    public function addToCart(Request $request)
    {
        $cookie_id_check    =   $request->hasCookie('scubaya_dive_in');
        $product_id         =   Input::get('id');
        $quantity           =   Input::get('quantity');

        if($cookie_id_check){
            $user_id    =   Cookie::get('scubaya_dive_in');
            if($request->hasCookie('products')){
                ProductCheckouts::transferCookieCartToDatabase();
            }
            $check      =   Products::where('id', $product_id)->exists();
            if ($check) {
                $course_already_in_cart = ProductCheckouts::where([
                    ['user_key', $user_id],
                    ['product_id', $product_id],
                    ['status', DIVE_CENTER_COURSE_PENDING]
                ])->exists();

                if (!$course_already_in_cart) {
                    $user_checkout_data = [
                        'user_key'          => $user_id,
                        'product_id'        => $product_id,
                        'quantity'          => $quantity,
                        'status'            => DIVE_CENTER_COURSE_PENDING
                    ];

                    ProductCheckouts::saveProductCheckoutData($user_checkout_data);
                    $count  =   ProductCheckouts::where([['user_key',$user_id],['status',DIVE_CENTER_COURSE_PENDING]])->count();
                    return response()->json(['status'   =>  'Product added to the cart.','count' => $count,'already' =>  0]);
                }
                return response()->json(['status'   =>  'Product already in cart!','already' =>  1]);
            }
        }else{
            $product_cart   =   $request->hasCookie('products')? unserialize(decrypt($_COOKIE['products'])) : [];

            if(array_key_exists($product_id,$product_cart)){
                return response()->json(['status'   =>  'Product already in cart!','already' =>  1]);
            }else{
                $product_cart[$product_id] = $quantity;
                return response()->json(['status'   =>  'Product added to the cart.','already' =>  0,'count' => count($product_cart) ])->withCookie(Cookie::make('products',serialize($product_cart),60*24*30,'/'));
            }
        }
    }

    public function changeProductQuantity(Request $request)
    {
        $is_user_logged_in      =   $request->hasCookie('scubaya_dive_in');
        $product_id             =   Input::get('product_id');
        $quantity               =   Input::get('quantity');

        if($is_user_logged_in){
            $user_id    =   Cookie::get('scubaya_dive_in');
            Cart::where([['user_key',$user_id], ['item_type', $request->type], ['item_id',$product_id],['status',CHECKOUT_PENDING]])
                ->update(['item_data'   =>  json_encode(['quantity' => $quantity ])]);

            return response()->json(true);
        }else{
            $product_cart                =   $request->hasCookie('product')? unserialize(Crypt::decrypt($_COOKIE['product'])) : [];
            $product_cart[$product_id]   =   [
                'quantity'  =>  $quantity
            ];
            return response()->json(true)->withCookie(Cookie::make('product', serialize($product_cart), 60*24, '/'));
        }
    }

    public function deleteProductItem(Request $request)
    {
        if($request->hasCookie('scubaya_dive_in')){
            $user_id            =   Cookie::get('scubaya_dive_in');
            Cart::where([['user_key',$user_id ], ['item_type', $request->type], ['item_id',$request->id]])->delete();
        }else{
            if($request->hasCookie('product')){
                $cart   =    unserialize(Crypt::decrypt($_COOKIE['product']));
                unset($cart[$request->id]);
                setcookie('product', Crypt::encrypt(serialize($cart)), time() + 86400 , '/');
            }
        }
        return redirect()->route('scubaya::checkout::cart');
    }
}