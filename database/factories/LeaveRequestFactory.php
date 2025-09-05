<?php

namespace Database\Factories;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LeaveRequest>
 */
class LeaveRequestFactory extends Factory
{
    public function definition(): array
    {
        $start = Carbon::today();
        $end = (clone $start)->addDays(fake()->numberBetween(0, 5));
        return [
            'employee_id' => Employee::factory(),
            'type' => fake()->randomElement(['annual', 'sick', 'unpaid', 'other']),
            'start_at' => $start,
            'end_at' => $end,
            'reason' => fake()->optional()->sentence(),
            'status' => 'pending',
        ];
    }
}
