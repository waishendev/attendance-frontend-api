<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectsControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function actingAs(User $user)
    {
        $token = auth('api')->login($user);
        return $this->withHeader('Authorization', 'Bearer ' . $token);
    }

    public function test_index_filters_active_and_keyword()
    {
        Project::factory()->create(['name' => 'Alpha', 'active' => true]);
        Project::factory()->create(['name' => 'Beta', 'active' => true]);
        Project::factory()->create(['name' => 'Gamma', 'active' => false]);

        $client = $this->actingAs(User::factory()->create());
        $res = $client->getJson('/api/projects?active=1&keyword=Al');
        $res->assertStatus(200);
        $data = $res->json('data');
        $this->assertCount(1, $data);
        $this->assertEquals('Alpha', $data[0]['name']);
    }

    public function test_locations_returns_locations()
    {
        $project = Project::factory()->hasLocations(2)->create();
        Project::factory()->hasLocations(1)->create();

        $client = $this->actingAs(User::factory()->create());
        $res = $client->getJson("/api/projects/{$project->id}/locations");
        $res->assertStatus(200);
        $this->assertCount(2, $res->json());
    }
}
