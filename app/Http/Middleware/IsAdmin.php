<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        /**
	    * add role attributes in user table
	    */
        if((Auth::user() && Auth::user()->role->name == 'admin') || Auth::user()->role->name == 'superadmin')
        {
            return $next($request);
        }

        return redirect('/main');
        }
}
