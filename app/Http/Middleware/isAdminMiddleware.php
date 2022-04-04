<?php

namespace App\Http\Middleware;
use Illuminate\Http\Response;
use Auth;

use Closure;

class isAdminMiddleware
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
        if($user->hasAnyRole('WebAdmin', 'SuperAdmin')){ //case sensitive
            return $next($request);
        }else{
            return new Response(view('unauthorized')->with('role', 'ADMIN'));
        }
    }
}
