<?php

namespace Tests\Feature;

use App\Events\CommentWritten;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class CommentControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testStoreCommentSuccessfully()
    {
        Event::fake([
            CommentWritten::class,
        ]);

        // Create a user for authentication
        $user = User::factory()->create();

        // Simulate authentication
        $this->actingAs($user);

        // Generate random comment data
        $commentData = [
            'body' => $this->faker->sentence,
        ];

        // Make a PUT request to store the comment
        $response = $this->put("/create-comment", $commentData);

        // Assert the response status code and message
        $response->assertStatus(201);
        $response->assertJson(['message' => 'Comment created successfully']);

        // Assert that the comment was stored in the database
        $this->assertDatabaseHas('comments', [
            'body' => $commentData['body'],
            'user_id' => $user->id,
        ]);

        // Assert that the CommentWritten event was dispatched
        Event::assertDispatched(CommentWritten::class, function ($event) use ($commentData) {
            return $event->comment->body === $commentData['body'];
        });
    }

    public function testStoreCommentValidation()
    {
        // Create a user for authentication
        $user = User::factory()->create();

        // Simulate authentication
        $this->actingAs($user);

        // Attempt to store a comment with invalid data
        $response = $this->put("/create-comment", [], ['accept' => 'application/json']);

        // Assert validation errors
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('body');
    }
}