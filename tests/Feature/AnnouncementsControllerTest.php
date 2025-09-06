<?php

namespace Tests\Feature;

use App\Models\Announcement;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AnnouncementsControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function actingAs(User $user)
    {
        $token = auth('api')->login($user);
        return $this->withHeader('Authorization', 'Bearer ' . $token);
    }

    public function test_feed_filters_and_orders()
    {
        $project = Project::factory()->create();
        Announcement::factory()->create(['project_id' => $project->id, 'created_at' => now()->subDay(), 'content' => 'old']);
        Announcement::factory()->create(['project_id' => $project->id, 'created_at' => now(), 'content' => 'new']);
        Announcement::factory()->create();

        $client = $this->actingAs(User::factory()->create());
        $res = $client->getJson('/api/announcements/feed?project_id=' . $project->id);
        $res->assertStatus(200);
        $this->assertCount(2, $res->json());
        $this->assertEquals('new', $res->json()[0]['content']);

        $res2 = $client->getJson('/api/announcements/feed?project_id=' . $project->id . '&after=' . now()->toDateTimeString());
        $res2->assertStatus(200);
        $this->assertCount(0, $res2->json());
    }
}
