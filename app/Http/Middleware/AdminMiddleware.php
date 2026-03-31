<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Admin ise admin paneline yönlendir
        if (Auth::user()->is_admin) {
            return redirect('/admin');
        }

        return $next($request);
    }
}