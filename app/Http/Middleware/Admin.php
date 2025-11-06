<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Admin
{

    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {

            if (auth()->user()->user_type == 'admin') {
                return $next($request);
            }else{
                // Return JSON response for API requests, redirect for web requests
                if ($request->expectsJson() || $request->is('api/*')) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Unauthorized access'
                    ], 403);
                }
                return redirect()->back();
            }
        }else{
            // Return JSON response for API requests, redirect for web requests
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Authentication required'
                ], 401);
            }
            return redirect()->route('login');
        }
    }
}
