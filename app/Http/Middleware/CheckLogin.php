<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckLogin
{
public function handle(Request $request, Closure $next)
    {
        if (empty(session('api_token'))) {
            return redirect('/login');
        }
        return $next($request);
    }
}
