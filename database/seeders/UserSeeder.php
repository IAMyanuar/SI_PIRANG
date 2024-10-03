<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'nim' => '362258302024',
                'nama' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'telp' => '081234567890',
                'role_user' => 'admin',
                'foto_bwp' => 'admin_bwp.png',
                'status_user' => 'terkonfirmasi',
            ],
            [
                'nim' => '362258302111',
                'nama' => 'Peminjam User',
                'email' => 'user@example.com',
                'password' => Hash::make('password'),
                'telp' => '081298765432',
                'role_user' => 'peminjam',
                'foto_bwp' => 'user_bwp.png',
                'status_user' => 'terkonfirmasi',
            ]
        ]);
    }
}
