<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Administrator Akademik',
                'email' => 'admin@example.com',
                'role' => UserRole::ADMIN,
            ],
            [
                'name' => 'Dosen Pengampu',
                'email' => 'dosen@example.com',
                'role' => UserRole::DOSEN,
            ],
            [
                'name' => 'Mahasiswa Akademik',
                'email' => 'mahasiswa@example.com',
                'role' => UserRole::MAHASISWA,
            ],
        ];

        foreach ($users as $user) {
            User::query()->updateOrCreate(
                ['email' => $user['email']],
                [
                    'name' => $user['name'],
                    'password' => Hash::make('password'),
                    'role' => $user['role'],
                    'email_verified_at' => now(),
                ],
            );
        }
    }
}
