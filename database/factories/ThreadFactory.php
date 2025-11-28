<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Thread>
 */
class ThreadFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            'userId' => User::factory(),
            'threadName' => $this->faker->sentence(3),
            'threadContent' => $this->faker->paragraph(2),
            'threadUpvote' => $this->faker->numberBetween(0, 20)
        ];
    }
}
