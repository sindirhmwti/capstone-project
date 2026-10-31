<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    /**
     * Display the public homepage.
     * TODO: Fetch featured workers from Supabase when integrated.
     */
    public function index()
    {
        // Dummy data — replace with Supabase queries later
        $featuredWorkers = collect([
            [
                'name'     => 'Siti Rahayu',
                'category' => 'ART',
                'rating'   => 4.8,
                'location' => 'Jakarta Selatan',
                'avatar'   => null,
            ],
            [
                'name'     => 'Dewi Lestari',
                'category' => 'Baby Sitter',
                'rating'   => 4.9,
                'location' => 'Bandung',
                'avatar'   => null,
            ],
            [
                'name'     => 'Nurul Aini',
                'category' => 'Perawat Lansia',
                'rating'   => 4.7,
                'location' => 'Surabaya',
                'avatar'   => null,
            ],
        ])->map(fn (array $worker) => (object) $worker);

        return view('public.home', compact('featuredWorkers'));
    }
}
