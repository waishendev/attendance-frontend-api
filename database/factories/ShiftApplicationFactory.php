<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\ShiftInstance;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ShiftApplication>
 */
class ShiftApplicationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'employee_id' => Employee::factory(),
            'shift_instance_id' => ShiftInstance::factory(),
            'status' => 'pending',
        ];
    }
}
