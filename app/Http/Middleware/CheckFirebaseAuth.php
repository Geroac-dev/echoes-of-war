<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckFirebaseAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!session()->has('firebase_user')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => '200',
                    'msg' => 'Unauthenticated.'
                ], 200);
            }
            return redirect('/');
        }

        return $next($request);
    }
}