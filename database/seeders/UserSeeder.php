<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        User::create([
            'name' => 'Admin',
            'email' => 'admin@admin',
            'password' => bcrypt('123456'),
            'role' => 'admin', // pastikan kolom role ada di tabel users
        ]);
        User::factory(10)->create();
    }
}
