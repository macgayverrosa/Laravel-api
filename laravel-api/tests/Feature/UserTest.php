<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class UserTest extends TestCase
{
    use RefreshDatabase; // Resets DB after each test

    // Test the application returns a successful response
    public function test_the_application_returns_a_successful_response(): void {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    // Test user registration
    public function test_user_can_register(){
        $response = $this->postJson('/api/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure(['status', 'success', 'message', 'user']);
    }

    // Test user login
    public function test_user_can_login(){
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),          
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure(['access_token', 'user']);
    }

    // Test user logout
    public function test_user_can_logout(){
        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->postJson('/api/logout', [], [
            'Authorization' => 'Bearer ' . $token,
        ]);


        $response->assertStatus(200)
                    ->assertJson(['message' => 'User logged out successfully']);
    }
}