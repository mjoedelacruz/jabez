<?php

namespace App\Http\Middleware;
use App\User;
use Auth;

use Closure;

class CheckUser
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
        if( Auth::user())
        {
           $user = Auth::user()->type;

           if ($user != 1) {
            return redirect('/sales');
            }
            return $next($request);
        }
        else
        {
             return redirect('/login');
        }
       
    }
}
