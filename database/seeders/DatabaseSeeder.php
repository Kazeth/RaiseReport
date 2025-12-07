<?php

namespace Database\Seeders;

use App\Models\File;
use App\Models\Thread;
use App\Models\Upvote;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(UserSeeder::class);
        Thread::factory(50)->create();
        Upvote::factory(100)->create();
        $this->call(FileSeeder::class);
    }
}
