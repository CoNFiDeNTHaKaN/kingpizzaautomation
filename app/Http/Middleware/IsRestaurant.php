<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class IsRestaurant
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
      if (!Auth::check()) {
        abort(401);
      }
      if (!Auth::user()->is_restaurant) {
        abort(401);
      }
      return $next($request);
    }
}
