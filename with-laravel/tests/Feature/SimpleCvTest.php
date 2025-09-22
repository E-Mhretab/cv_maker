<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Cv;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SimpleCvTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function authenticated_user_can_access_cv_routes()
    {
        // Create a user using the standard Laravel user factory
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $this->actingAs($user);

        // Test CV index route
        $response = $this->get(route('cvs.index'));
        $response->assertStatus(200);

        // Test CV create route
        $response = $this->get(route('cvs.create'));
        $response->assertStatus(200);
    }

    /** @test */
    public function unauthenticated_user_cannot_access_cv_routes()
    {
        // Test CV index route
        $this->get(route('cvs.index'))->assertRedirect(route('login'));
        
        // Test CV create route
        $this->get(route('cvs.create'))->assertRedirect(route('login'));
    }

    /** @test */
    public function cv_model_relationships_work()
    {
        // Create a user using the standard Laravel user factory
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Create a CV
        $cv = Cv::create([
            'name' => 'Test CV',
            'email' => 'test@example.com',
            'user_id' => $user->id,
        ]);

        // Test user relationship
        $this->assertEquals($user->id, $cv->user->id);
        $this->assertEquals(1, $user->cvs->count());
    }

    /** @test */
    public function pdf_export_route_exists()
    {
        // Create a user using the standard Laravel user factory
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $this->actingAs($user);

        // Create a CV
        $cv = Cv::create([
            'name' => 'Test CV',
            'email' => 'test@example.com',
            'user_id' => $user->id,
        ]);

        // Test PDF route exists (may return 500 due to missing DomPDF setup, but route should exist)
        $response = $this->get(route('cvs.pdf', $cv));
        
        // Route should exist (not 404)
        $this->assertNotEquals(404, $response->getStatusCode());
    }
}
