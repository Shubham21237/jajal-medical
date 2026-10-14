<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test 1: Valid user login redirects to dashboard.
     */
    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'intern@jajal.com',
            'password' => Hash::make('secret123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'intern@jajal.com',
            'password' => 'secret123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    /**
     * Test 2: Invalid login is rejected with validation error.
     */
    public function test_user_cannot_login_with_invalid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'intern@jajal.com',
            'password' => Hash::make('secret123'),
        ]);

        $response = $this->from('/login')->post('/login', [
            'email' => 'intern@jajal.com',
            'password' => 'wrong-password',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /**
     * Test 3: Guests cannot access protected pages or APIs.
     */
    public function test_guests_cannot_access_protected_pages_or_apis(): void
    {
        // Protected web routes redirect to login
        $responseWeb = $this->get('/dashboard');
        $responseWeb->assertRedirect('/login');

        $responseCases = $this->get('/cases');
        $responseCases->assertRedirect('/login');

        // Protected API routes return 401 Unauthorized
        $responseApi = $this->getJson('/api/cases');
        $responseApi->assertStatus(401);
    }

    /**
     * Test: API login returns valid Sanctum bearer token.
     */
    public function test_api_login_returns_token(): void
    {
        $user = User::factory()->create([
            'email' => 'apiuser@jajal.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'apiuser@jajal.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'user' => ['id', 'name', 'email', 'role'],
                    'token',
                    'token_type',
                ],
            ]);
    }
}
