<?php

namespace Tests\Feature\Auth;

use App\Models\Organization;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        $organization = Organization::factory()->create(['name' => 'Gapoktan Demo']);

        $response = $this->post('/register', [
            'name' => 'Test User',
            'organization_id' => $organization->id,
            'role' => 'petani',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('petani.dashboard', absolute: false));
    }
}
