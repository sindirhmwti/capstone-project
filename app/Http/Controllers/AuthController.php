<?php

namespace App\Http\Controllers;

class AuthController extends Controller
{
    /**
     * Show the login page.
     */
    public function showLogin()
    {
        return view('public.login');
    }

    /**
     * Show the registration page.
     */
    public function showRegister()
    {
        return view('public.register');
    }

    /**
     * Show the role selection page.
     */
    public function showSelectRole()
    {
        return view('public.select-role');
    }

    /**
     * Handle login.
     * TODO: Integrate with Supabase Auth (signInWithPassword).
     */
    public function login()
    {
        // Placeholder — redirect based on user role from Supabase session
        // $user = SupabaseAuth::signIn(request('email'), request('password'));
        // Redirect based on $user->role
        return redirect()->route('home');
    }

    /**
     * Handle registration.
     * TODO: Integrate with Supabase Auth (signUp).
     */
    public function register()
    {
        // Placeholder — create user in Supabase, then redirect to select-role
        return redirect()->route('register.select-role');
    }

    /**
     * Handle logout.
     * TODO: Integrate with Supabase Auth (signOut).
     */
    public function logout()
    {
        // $supabase->auth->signOut();
        return redirect()->route('home');
    }
}

