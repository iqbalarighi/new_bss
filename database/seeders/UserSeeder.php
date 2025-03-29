<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Superadmin',
            'email' => 'super@mail.com',
            'password' => Hash::make('blackid'),
            'role' => '0',
        ]);
    }
}
