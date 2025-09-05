<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Employee;
use App\Models\LeaveRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeavesControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function actingAsUser(User $user)
    {
        $token = auth('api')->login($user);
        return $this->withHeader('Authorization', 'Bearer ' . $token);
    }

    public function test_index_returns_paginated_leaves()
    {
        $employee = Employee::factory()->create();
        LeaveRequest::factory()->count(3)->create(['employee_id' => $employee->id]);
        // another employee's leaves shouldn't show
        LeaveRequest::factory()->count(2)->create();

        $client = $this->actingAsUser($employee->user);
        $response = $client->getJson('/api/leaves/my');

        $response->assertStatus(200)
            ->assertJson([
                'total' => 3,
            ]);
        $this->assertCount(3, $response->json('data'));
    }

    public function test_store_creates_leave_request()
    {
        $employee = Employee::factory()->create();
        $payload = [
            'type' => 'annual',
            'start_at' => now()->toDateTimeString(),
            'end_at' => now()->addDay()->toDateTimeString(),
            'reason' => 'vacation',
        ];

        $client = $this->actingAsUser($employee->user);
        $response = $client->postJson('/api/leaves', $payload);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'type' => 'annual',
                'reason' => 'vacation',
                'status' => 'pending',
            ]);

        $this->assertDatabaseHas('leave_requests', [
            'employee_id' => $employee->id,
            'type' => 'annual',
            'reason' => 'vacation',
        ]);
    }

    public function test_cancel_only_pending()
    {
        $employee = Employee::factory()->create();
        $pending = LeaveRequest::factory()->create(['employee_id' => $employee->id]);
        $approved = LeaveRequest::factory()->create(['employee_id' => $employee->id, 'status' => 'approved']);

        $client = $this->actingAsUser($employee->user);

        $client->putJson("/api/leaves/{$pending->id}/cancel")
            ->assertStatus(200)
            ->assertJsonFragment(['status' => 'rejected']);

        $client->putJson("/api/leaves/{$approved->id}/cancel")
            ->assertStatus(422)
            ->assertJson(['message' => 'Only pending can be cancelled']);
    }
}
