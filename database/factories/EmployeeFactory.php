<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'emp_no' => fake()->unique()->bothify('EMP###'),
            'department_id' => Department::factory(),
            'position' => fake()->jobTitle(),
            'hire_date' => fake()->date(),
            'status' => 'active',
        ];
    }
}
