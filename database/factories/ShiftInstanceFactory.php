<?php

namespace Database\Factories;

use App\Models\Location;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ShiftInstance>
 */
class ShiftInstanceFactory extends Factory
{
    public function definition(): array
    {
        $start = $this->faker->dateTimeBetween('+1 days', '+10 days');
        $end = (clone $start)->modify('+8 hours');
        $project = Project::factory();
        return [
            'project_id' => $project,
            'location_id' => Location::factory()->for($project),
            'start_at' => $start,
            'end_at' => $end,
        ];
    }
}
