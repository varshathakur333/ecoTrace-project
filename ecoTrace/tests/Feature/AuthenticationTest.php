<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test guest can view login and register pages.
     */
    public function test_guest_can_view_auth_pages()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);

        $response = $this->get('/register');
        $response->assertStatus(200);
    }

    /**
     * Test normal user registration.
     */
    public function test_user_can_register()
    {
        $response = $this->post('/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'user',
            'phone' => '+91 99999 88888',
            'address' => '123 Green Street, Delhi',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
            'role' => 'user',
        ]);
    }

    /**
     * Test collector registration requires extra fields.
     */
    public function test_collector_registration_requires_business_documentation()
    {
        $response = $this->post('/register', [
            'name' => 'Agency Rep',
            'email' => 'agency@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'collector',
            'phone' => '+91 99999 88888',
            'address' => 'Recycling Hub, Noida',
            // Missing business name & license
        ]);

        $response->assertSessionHasErrors(['business_name', 'license_no']);
    }

    /**
     * Test successful collector registration and request log.
     */
    public function test_collector_can_register_with_correct_documentation()
    {
        $response = $this->post('/register', [
            'name' => 'Agency Rep',
            'email' => 'agency@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'collector',
            'phone' => '+91 99999 88888',
            'address' => 'Recycling Hub, Noida',
            'business_name' => 'Earth Cycle Inc.',
            'license_no' => 'LIC-EW-2026',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertDatabaseHas('users', [
            'email' => 'agency@example.com',
            'role' => 'collector',
            'is_verified' => false,
        ]);

        $this->assertDatabaseHas('verification_requests', [
            'business_name' => 'Earth Cycle Inc.',
            'license_no' => 'LIC-EW-2026',
            'status' => 'pending',
        ]);
    }
}
