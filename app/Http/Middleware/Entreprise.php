<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class Entreprise
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        /**
         * get route parametre 
         * I can also use this : $id_entreprise = $request->route('id'); 
         */

        $id_entreprise = $request->route()->parameter('id');
        $id_user = Auth::user()->id;

        $manage = DB::table('manages')->where([
            'id_user' => $id_user,
            'id_entreprise' => $id_entreprise,
        ])->first();

        $entreprise = DB::table('entreprises')->where('id_user', Auth::user()->id)->first();

        if($manage || $entreprise)
        {
            return $next($request);
        }

        return redirect()->back();
    }
}
