<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            ['name' => 'Alice', 'email' => 'alice@mail.com', 'password' => Hash::make('123456')],
            ['name' => 'Bob', 'email' => 'bob@mail.com', 'password' => Hash::make('123456')],
        ];

        User::upsert($users, ['email'], ['name', 'password']);
    }
}
