<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationRedirectTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_registration_redirects_to_user_dashboard()
    {
        $response = $this->post('/register', [
            'name'                  => 'Flow Test User',
            'email'                 => 'flowtest@example.com',
            'phone'                 => '08123456789',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
            'terms'                 => 'on',
        ]);

        // Assert redirect to the USER dashboard, not admin
        $response->assertRedirect(route('dashboard'));

        // The logged-in user must have 'user' role
        $this->assertAuthenticated();
        $this->assertFalse(auth()->user()->isAdmin());
    }

    public function test_admin_login_redirects_to_admin_dashboard()
    {
        \App\Models\User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => 'password123',
            'role' => 'admin',
        ]);

        $response = $this->post('/login', [
            'email'    => 'admin@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
    }
}
