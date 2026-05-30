<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Session;
use Symfony\Component\HttpFoundation\Response;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

    public function handle(Request $request, Closure $next): Response
    {
        // Jika tidak ada token ATAU role bukan admin, langsung lempar error 403
        if(!Session::has('api_token') || Session::get('user_data')['role'] !== 'admin'){
            abort(403); 
        }
        
        return $next($request);
    }
}
