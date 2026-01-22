<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(),
            'description' => $this->faker->optional()->paragraph(),
            'due_date' => null,
            'start_time' => null,
            'end_time' => null,
            'priority' => 'medium',
            'status' => 'pending',
            'user_id' => User::factory(),
        ];
    }
}
