<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class checkAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(auth()->user()->role==1)
        {
            return $next($request);
        }
        else
        {
            return redirect('Account/User')->with('error',"You do not have permission to Access admin panel");
           
        }
    }
}
