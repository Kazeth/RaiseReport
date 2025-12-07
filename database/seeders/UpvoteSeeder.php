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
        Upvote::factory(50)->create();
    }
}
