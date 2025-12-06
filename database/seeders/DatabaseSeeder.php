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
        Upvote::factory(200)
            ->make()
            ->unique(fn($u) => $u->user_id . '-' . $u->thread_id)
            ->each(fn($u) => $u->save());

        $this->call(FileSeeder::class);
    }
}
