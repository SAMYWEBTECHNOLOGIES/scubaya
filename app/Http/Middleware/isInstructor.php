<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class isInstructor
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
        if((Auth::guard('merchant')->check() && Auth::guard('merchant')->user()->role_id == INSTRUCTOR_ROLE_ID) || !Auth::guard('merchant')->check() ){
            return $next($request);
        }
        elseif(Auth::guard('merchant')->check()){
            return redirect()->route('scubaya::merchant::dashboard',[Auth::guard('merchant')->user()->id]);
        }

        return redirect()->to(route('scubaya::merchant::login'));
    }
}
