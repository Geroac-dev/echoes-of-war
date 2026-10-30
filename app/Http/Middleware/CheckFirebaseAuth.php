<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckFirebaseAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        // Verificamos si existe nuestra sesión personalizada de Firebase
        if (!session()->has('firebase_user')) {
            return response()->json([
                'status' => '401',
                'msg' => 'Unauthenticated.'
            ], 401);
        }

        return $next($request);
    }
}