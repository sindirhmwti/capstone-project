<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_admin_dashboard_displays_pending_verification_dates(): void
    {
        $response = $this->get('/admin/dashboard');

        $response->assertStatus(200)
            ->assertSee('Diajukan: 2026-10-30');
    }

    public function test_client_dashboard_displays_without_missing_sidebar_routes(): void
    {
        $this->get('/client/dashboard')->assertOk();
    }

    public function test_worker_dashboard_displays_without_missing_sidebar_routes(): void
    {
        $this->get('/worker/dashboard')->assertOk();
    }
}
