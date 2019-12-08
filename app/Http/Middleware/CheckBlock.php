<?php

namespace App\Http\Middleware;

use Closure;

class CheckBlock
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
        if($request->user()->status==config('constants.STATUS.BLOCK')){
            \Auth::logout();
            return redirect('/login')->withErrors(['username'=>'You are blocked/suspended.']);
        }
        return $next($request);
    }
}
