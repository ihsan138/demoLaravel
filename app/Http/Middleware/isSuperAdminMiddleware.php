<?php

namespace App\Http\Middleware;
use Illuminate\Http\Response;
use Auth;


use Closure;

class isSuperAdminMiddleware
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
        $user = Auth::user();
        if($user->hasRole('SuperAdmin')){ //case sensitive
            return $next($request);
        }else{
            return new Response(view('unauthorized')->with('role', 'SUPER ADMIN'));
        }

    }
}
