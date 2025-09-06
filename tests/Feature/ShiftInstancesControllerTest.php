<?php

namespace Tests\Feature;

use App\Models\ShiftInstance;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShiftInstancesControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function actingAs(User $user)
    {
        $token = auth('api')->login($user);
        return $this->withHeader('Authorization', 'Bearer ' . $token);
    }

    public function test_index_filters_by_params()
    {
        $shiftA = ShiftInstance::factory()->create([
            'start_at' => now()->addDays(2),
        ]);
        // Different location and project
        ShiftInstance::factory()->create([
            'project_id' => $shiftA->project_id,
            'location_id' => $shiftA->location_id,
            'start_at' => now()->addDays(5),
        ]);
        ShiftInstance::factory()->create();

        $client = $this->actingAs(User::factory()->create());
        $res = $client->getJson('/api/shift-instances?project_id=' . $shiftA->project_id . '&location_id=' . $shiftA->location_id . '&date_from=' . now()->addDay()->toDateString() . '&date_to=' . now()->addDays(3)->toDateString());
        $res->assertStatus(200);
        $this->assertCount(1, $res->json('data'));
    }
}
