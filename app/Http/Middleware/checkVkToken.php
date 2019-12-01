<?php

namespace App\Http\Middleware;

use Closure;
use http\Cookie;

class checkVkToken
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
        if(\Illuminate\Support\Facades\Cookie::get('vkToken') !== null){
            return $next($request);
        }else {
            return redirect('api/vk/auth');
        }

    }
}
