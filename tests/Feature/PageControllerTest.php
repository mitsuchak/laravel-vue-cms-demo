<?php
namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PageControllerTest extends TestCase
{
    use RefreshDatabase; // Reset the database after each test

    /**
     * Test creating a new page via API.
     */
    public function test_store_page()
    {
        // Simulate a POST request to the API endpoint
        $response = $this->postJson('/api/pages', [
            'title' => 'Test Page',
            'slug' => 'test-page',
            'content' => 'This is a test page.',
            'parent_id' => null,
        ]);

        // Assert that the response status is 201 (Created)
        $response->assertStatus(201);

        // Assert that the page was created in the database
        $this->assertDatabaseHas('pages', [
            'title' => 'Test Page',
            'slug' => 'test-page',
        ]);

        // Assert that the response contains the created page data
        $response->assertJson([
            'data' => [
                'title' => 'Test Page',
                'slug' => 'test-page',
            ],
        ]);
    }

    /**
     * Test updating an existing page via API.
     */
    public function test_update_page()
    {
        // Create a page in the database
        $page = Page::factory()->create();

        // Simulate a PUT request to the API endpoint
        $response = $this->putJson("/api/pages/{$page->id}", [
            'title' => 'Updated Page',
            'slug' => 'updated-page',
            'content' => 'This is an updated page.',
            'parent_id' => null,
        ]);

        // Assert that the response status is 200 (OK)
        $response->assertStatus(200);

        // Assert that the page was updated in the database
        $this->assertDatabaseHas('pages', [
            'id' => $page->id,
            'title' => 'Updated Page',
            'slug' => 'updated-page',
        ]);

        // Assert that the response contains the updated page data
        $response->assertJson([
            'data' => [
                'title' => 'Updated Page',
                'slug' => 'updated-page',
            ],
        ]);
    }

    /**
     * Test deleting a page via API.
     */
    public function test_destroy_page()
    {
        // Create a page in the database
        $page = Page::factory()->create();

        // Simulate a DELETE request to the API endpoint
        $response = $this->deleteJson("/api/pages/{$page->id}");

        // Assert that the response status is 204 (No Content)
        $response->assertStatus(204);

        // Assert that the page was deleted from the database
        $this->assertDatabaseMissing('pages', [
            'id' => $page->id,
        ]);
    }

    /**
     * Test validation when creating a page via API.
     */
    public function test_store_page_validation()
    {
        // Simulate a POST request with invalid data
        $response = $this->postJson('/api/pages', [
            'title' => '', // Required field is empty
            'slug' => '', // Required field is empty
            'content' => '', // Required field is empty
            'parent_id' => null,
        ]);

        // Assert that the response status is 422 (Unprocessable Entity)
        $response->assertStatus(422);

        // Assert that the response contains validation errors
        $response->assertJsonValidationErrors(['title', 'slug', 'content']);
    }
}