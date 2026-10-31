<?php

namespace App\Services;

/**
 * SupabaseService
 *
 * Placeholder for Supabase integration.
 * TODO: Implement real API calls to Supabase REST/Auth endpoints.
 *
 * Required .env variables:
 *   SUPABASE_URL=https://your-project.supabase.co
 *   SUPABASE_ANON_KEY=your-anon-key
 *   SUPABASE_SERVICE_ROLE_KEY=your-service-role-key
 */
class SupabaseService
{
    protected string $url;
    protected string $anonKey;
    protected string $serviceKey;

    public function __construct()
    {
        $this->url        = config('services.supabase.url', env('SUPABASE_URL', ''));
        $this->anonKey    = config('services.supabase.anon_key', env('SUPABASE_ANON_KEY', ''));
        $this->serviceKey = config('services.supabase.service_role_key', env('SUPABASE_SERVICE_ROLE_KEY', ''));
    }

    /**
     * Sign in with email and password via Supabase Auth.
     * TODO: Implement using Guzzle HTTP client.
     */
    public function signIn(string $email, string $password): ?array
    {
        // Example implementation:
        // $response = Http::withHeaders([
        //     'apikey' => $this->anonKey,
        //     'Content-Type' => 'application/json',
        // ])->post($this->url . '/auth/v1/token?grant_type=password', [
        //     'email' => $email,
        //     'password' => $password,
        // ]);
        //
        // if ($response->successful()) {
        //     return $response->json();
        // }
        //
        // return null;
        return null; // Placeholder
    }

    /**
     * Sign up a new user via Supabase Auth.
     * TODO: Implement using Guzzle HTTP client.
     */
    public function signUp(string $email, string $password, array $metadata = []): ?array
    {
        // Example:
        // $response = Http::withHeaders([
        //     'apikey' => $this->anonKey,
        //     'Content-Type' => 'application/json',
        // ])->post($this->url . '/auth/v1/signup', [
        //     'email' => $email,
        //     'password' => $password,
        //     'data' => $metadata,
        // ]);
        //
        // return $response->json();
        return null; // Placeholder
    }

    /**
     * Get user data by JWT token.
     * TODO: Implement using Supabase Admin API.
     */
    public function getUser(string $token): ?array
    {
        // Example:
        // $response = Http::withHeaders([
        //     'apikey'        => $this->serviceKey,
        //     'Authorization' => 'Bearer ' . $token,
        // ])->get($this->url . '/auth/v1/user');
        //
        // return $response->ok() ? $response->json() : null;
        return null; // Placeholder
    }

    /**
     * Query a Supabase table (PostgREST).
     * TODO: Implement generic table query.
     */
    public function from(string $table): array
    {
        // Example:
        // $response = Http::withHeaders([
        //     'apikey'        => $this->serviceKey,
        //     'Authorization' => 'Bearer ' . $this->serviceKey,
        // ])->get($this->url . '/rest/v1/' . $table);
        //
        // return $response->json() ?? [];
        return []; // Placeholder
    }
}

