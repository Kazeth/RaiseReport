<?php

namespace Database\Factories;

use App\Models\Thread;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\File>
 */
class FileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $extensions = ['jpg', 'png', 'jpeg', 'pdf', 'mp4', 'mp3', 'docx'];

        // ambil thread random
        $thread = Thread::inRandomOrder()->first();

        return [
            'thread_id' => $thread ? $thread->id : null,
            'fileName' => $this->faker->uuid . '.' . $this->faker->randomElement($extensions),

            // path dummy (file fisik TIDAK ADA)
            'path' => 'attachments/' . $this->faker->uuid
                . '.' . $this->faker->randomElement($extensions),
        ];
    }
}
