<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Post;

class PostTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;
    protected $token;

    public function setUp(): void {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->token = $this->user->createToken('auth_token')->plainTextToken;
    }

    // Test creating a post
    public function test_user_can_create_post() {
        $response = $this->postJson('/api/posts', [
            'title' => 'Test Post',
            'content' => 'This is a test post content.',
        ], [
            'Authorization' => 'Bearer ' . $this->token,
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure(['status', 'success', 'message', 'data']);
    }

    // Test fetching all posts
    public function test_user_can_get_all_posts() {
        $create = $this->postJson('/api/posts', [
            'title' => 'Test Post',
            'content' => 'This is a test post content.',
        ], [
            'Authorization' => 'Bearer ' . $this->token,
        ]);

        $response = $this->getJson('/api/posts', [
            'Authorization' => 'Bearer ' . $this->token,
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure(['status', 'success', 'data']);
    }

    // Test deleting a post
    public function test_user_can_delete_own_post() {
        $postResponse = $this->postJson('/api/posts', [
            'title' => 'Test Post',
            'content' => 'This is a test post content.',
        ], [
            'Authorization' => 'Bearer ' . $this->token,
        ]);

        // Extract post ID from the response
        $postId = $postResponse->json('data.id'); // Use correct JSON key path

        $response = $this->deleteJson("/api/posts/{$postId}", [], [
            'Authorization' => 'Bearer ' . $this->token,
        ]);

        $response->assertStatus(200)
                    ->assertJson(['message' => 'Post deleted']);
    }

}