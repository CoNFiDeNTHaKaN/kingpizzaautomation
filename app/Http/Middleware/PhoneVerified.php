<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class PhoneVerified
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

        if(!Auth::check()) 
        return redirect()->route('user.login');

        if(Auth::user()->phone_verified_at==null)
        return redirect()->route('user.verifyPhone');
        else
        return $next($request);
    }
}
