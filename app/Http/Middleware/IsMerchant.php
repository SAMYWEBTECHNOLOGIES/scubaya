<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class IsMerchant
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(Auth::check()){

            if(Auth::user()->is_merchant) {
                return $next($request);
            }

            if (Auth::user()->is_merchant_user) {
                return $next($request);
            }

            if (Auth::user()->is_admin) {
                return Redirect::to(route('scubaya::admin::login'));
            }

            if (Auth::user()->is_user) {
                return Redirect::to(route('scubaya::user::login'));
            }

            return Redirect::to(route('scubaya::home'));

            /*switch(Auth::user()->is_merchant){
                case MERCHANT :
                    return $next($request);
                    break;

                case MERCHANT_USER_ROLE :
                    return $next($request);
                    break;

                case ADMIN :
                    return Redirect::to(route('scubaya::admin::login'));

                default :
                    return Redirect::to(route('scubaya::index'));
                    break;
            }*/
        }
        else {
            return $next($request);
        }
    }
}
