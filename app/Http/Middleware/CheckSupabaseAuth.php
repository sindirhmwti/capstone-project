<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware: Check Supabase Auth Session
 *
 * TODO: Replace dummy logic with real Supabase session check.
 * The Supabase JS client stores the session in localStorage.
 * For server-side verification, pass the JWT token in the Authorization header
 * and verify it using the Supabase secret or the Supabase Admin API.
 *
 * Example flow:
 * 1. Frontend: supabase.auth.getSession() → get access_token
 * 2. Send token in request header: Authorization: Bearer {access_token}
 * 3. Middleware verifies token via Supabase Admin API
 * 4. Extract user role from user_metadata or profiles table
 */
class CheckSupabaseAuth
{
    public function handle(Request $request, Closure $next, string $role = 'any'): Response
    {
        // TODO: Implement Supabase JWT verification
        // $token = $request->bearerToken() ?? $request->cookie('sb-token');
        //
        // if (!$token) {
        //     return redirect()->route('login')->with('error', 'Silakan masuk terlebih dahulu.');
        // }
        //
        // $user = SupabaseService::verifyToken($token);
        //
        // if (!$user) {
        //     return redirect()->route('login')->with('error', 'Sesi Anda telah berakhir.');
        // }
        //
        // if ($role !== 'any' && $user->role !== $role) {
        //     abort(403, 'Akses ditolak.');
        // }
        //
        // $request->merge(['auth_user' => $user]);

        return $next($request);
    }
}

