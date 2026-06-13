<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'id_number' => 'ADMIN01',
                'first_name' => 'Admin',
                'middle_initial' => 'A',
                'last_name' => 'User',
                'password' => Hash::make('admin12345'),
                'usertype' => 'admin',
            ]
        );
    }
}
