<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class MenuAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $id_functionalU = $request->route()->parameter('id2');
        $group = $request->route()->parameter('group');
        $user = Auth::user();
        $routeName = request()->route()->getName();

        //$permission = DB::table('permissions')->where('name', $routeName)->first();

        $permission_assign = DB::table('permission_assigns')
            ->where([
                'id_user' => $user->id,
                'id_fu' => $id_functionalU,
                //'id_perms' => $permission->id,
                'group' => $group,
        ])->first();

        if($permission_assign || Auth::user()->role->name == "admin" || Auth::user()->role->name == "superadmin")
        {
            return $next($request);
        }

        return redirect()->back();
    }
}
