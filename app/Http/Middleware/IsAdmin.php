<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class IsAdmin
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

            if(Auth::user()->is_admin) {
                return $next($request);
            }

            if(Auth::user()->is_merchant) {
                return Redirect::to(route('scubaya::merchant::index'));
            }

            if(Auth::user()->is_merchant_user) {
                return Redirect::to(route('scubaya::merchant::index'));
            }

            if(Auth::user()->is_user) {
                return Redirect::to(route('scubaya::user::login'));
            }

            /*switch(Auth::user()->role_id){
                case MERCHANT :
                    return Redirect::to(route('scubaya::merchant::index'));
                    break;

                case ADMIN :
                    return $next($request);

                case INSTRUCTOR :
                    return Redirect::to(route('scubaya::merchant::index'));
                    break;

                default :
                    return Redirect::to(route('scubaya::index'));
                    break;
            }*/
        } else {
            return $next($request);
        }
    }
}
