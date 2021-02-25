<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class CheckIp
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
        $ip = $request->ip();
        
        if(Auth::check()){
            $check=\App\BannedIp::where('email',Auth::user()->email)->get();
            if(count($check)>0){
                if($request->path()!='banned'){
                    return redirect('banned');
                }
            }
        }

        $check=\App\BannedIp::where('ip',$request->ip())->get();
        if(count($check)>0){
            if($request->path()!='banned'){
                return redirect('banned');
            }
        }
        

        return $next($request);
    }
}
