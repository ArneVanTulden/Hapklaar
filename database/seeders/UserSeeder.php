<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'arne.zeekoe@gmail.com'],
            [
                'username' => 'arne',
                'password' => 'password',
                'role'     => 'admin',
            ]
        );
    }
}
