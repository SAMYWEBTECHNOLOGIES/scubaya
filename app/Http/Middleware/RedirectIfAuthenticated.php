<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if(Auth::check()){

            if(Auth::user()->is_admin) {
                return redirect()->route('scubaya::admin::dashboard');
            }

            if(Auth::user()->is_merchant) {
                return redirect()->route('scubaya::merchant::dashboard', [Auth::id()]);
            }

            if(Auth::user()->is_merchant_user) {
                if(Request::getHost() == env('MERCHANT_URL')) {
                    return redirect()->route('scubaya::merchant::dashboard', [Auth::id()]);
                }
            }

            if(Auth::user()->is_user) {
                if(Request::getHost() == env('USER_URL')){
                    return redirect()->route('scubaya::user::dashboard');
                }
            }

            return redirect()->route('scubaya::home');
            /*switch(Auth::user()->role_id){
                case ADMIN:
                    return redirect()->route('scubaya::admin::dashboard');
                    break;

                case MERCHANT:
                    return redirect()->route('scubaya::merchant::dashboard', [Auth::id()]);
                    break;

                case MERCHANT_USER_ROLE:
                    return redirect()->route('scubaya::merchant::dashboard', [Auth::id()]);
                    break;

                case USER:
                    return redirect()->route('scubaya::user::dashboard');
                    break;

                default :
                    return redirect()->route('scubaya::home');
            }*/
        }

        return $next($request);
    }
}
