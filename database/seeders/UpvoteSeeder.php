<?php

namespace Database\Seeders;

use App\Models\Upvote;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UpvoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Upvote::factory(20)
            ->make()
            ->unique(fn($u) => $u->user_id . '-' . $u->thread_id)
            ->each(fn($u) => $u->save());
    }
}
