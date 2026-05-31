<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'id_number' => 'ADMIN01',
                'first_name' => 'Admin',
                'middle_initial' => 'A',
                'last_name' => 'User',
                'password' => bcrypt('admin12345'),
                'usertype' => 'admin',
            ]
        );
    }
}
