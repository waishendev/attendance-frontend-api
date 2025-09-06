<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\ShiftApplication;
use App\Models\ShiftInstance;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShiftApplicationsControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function actingAs(Employee $employee)
    {
        $token = auth('api')->login($employee->user);
        return $this->withHeader('Authorization', 'Bearer ' . $token);
    }

    public function test_store_is_idempotent_and_my_lists()
    {
        $employee = Employee::factory()->create();
        $shift = ShiftInstance::factory()->create();
        $client = $this->actingAs($employee);

        $payload = ['shift_instance_id' => $shift->id];
        $client->postJson('/api/shift-applications', $payload)
            ->assertStatus(201);
        $client->postJson('/api/shift-applications', $payload)
            ->assertStatus(200);

        $res = $client->getJson('/api/shift-applications/my');
        $res->assertStatus(200);
        $this->assertCount(1, $res->json('data'));
    }

    public function test_cancel_only_pending()
    {
        $employee = Employee::factory()->create();
        $pending = ShiftApplication::factory()->create(['employee_id' => $employee->id]);
        $approved = ShiftApplication::factory()->create(['employee_id' => $employee->id, 'status' => 'approved']);

        $client = $this->actingAs($employee);
        $client->putJson("/api/shift-applications/{$pending->id}/cancel")
            ->assertStatus(200)
            ->assertJsonFragment(['status' => 'cancelled']);

        $client->putJson("/api/shift-applications/{$approved->id}/cancel")
            ->assertStatus(422);
    }
}
