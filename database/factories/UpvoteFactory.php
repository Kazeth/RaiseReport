<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Thread;
use App\Models\Upvote;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Upvote>
 */
class UpvoteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        static $combos = null;

        if (!$combos) {
            $users = User::pluck('id')->toArray();
            $threads = Thread::where('threadStatus', 'approved')->pluck('id')->toArray();

            if (empty($users) || empty($threads)) {
                throw new \Exception("Tidak ada user atau thread approved untuk membuat upvote.");
            }

            // Ambil kombinasi unik yang belum ada di database
            $existing = Upvote::pluck('thread_id', 'user_id')->mapWithKeys(fn($thread_id, $user_id) => ["$user_id-$thread_id" => true]);

            $combos = collect($users)
                ->crossJoin($threads)
                ->reject(fn($pair) => isset($existing["{$pair[0]}-{$pair[1]}"]))
                ->shuffle()
                ->map(fn($pair) => [
                    'user_id' => $pair[0],
                    'thread_id' => $pair[1],
                ])
                ->toArray();
        }

        $combo = array_pop($combos);

        if (!$combo) {
            throw new \Exception("Sudah tidak ada kombinasi unik tersisa untuk upvote.");
        }

        return $combo;
    }
}
