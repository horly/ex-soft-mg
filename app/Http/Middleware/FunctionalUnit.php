<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class FunctionalUnit
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $id_functionalU = $request->route()->parameter('id2');
        $id_entreprise = $request->route()->parameter('id');
        $user = Auth::user();

        $manage = DB::table('manage_f_u_s')->where([
            'id_user' => $user->id,
            'id_entreprise' => $id_entreprise,
            'id_fu' => $id_functionalU,
        ])->first();

        $entreprise = DB::table('entreprises')
                    ->where([
                        'id' => $id_entreprise,
                        'sub_id' => $user->sub_id
        ])->first();
        
        if($user->role->name == "admin")
        {
            if($entreprise)
            {
                return $next($request);
            }
        }
        else
        {
            if($manage)
            {
                return $next($request);
            }
        }

        return redirect()->back();
    }
}
