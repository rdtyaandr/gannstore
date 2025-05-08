<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'GannStore',
            'email' => 'eganmhs@gmail.com',
            'password' => Hash::make('egan2010'),
            'email_verified_at' => now(),
        ]);
    }
}
