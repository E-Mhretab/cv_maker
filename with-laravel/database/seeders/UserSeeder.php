<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'id' => 1,
                'username' => 'admin',
                'email' => 'admin@cvsystem.com',
                'password_hash' => '$2y$12$PARQcIq4w9BqakCNtEPF0OH0TSTZ5I0.YjQPX4.x74hUnE6YQttym',
                'role' => 'admin',
                'is_active' => 1,
                'created_at' => '2025-09-12 09:55:16',
                'updated_at' => '2025-09-19 12:03:33',
                'last_login' => '2025-09-19 12:03:33'
            ],
            [
                'id' => 2,
                'username' => 'john_doe',
                'email' => 'john.doe@example.com',
                'password_hash' => '$2y$12$AwTv3ctQP7PTNjiM1PxBf.a5fnwO2o2rfkjnswya1s3iof6FocLCm',
                'role' => 'user',
                'is_active' => 1,
                'created_at' => '2025-09-12 16:06:47',
                'updated_at' => '2025-09-19 10:54:58',
                'last_login' => '2025-09-19 10:54:58'
            ],
            [
                'id' => 3,
                'username' => 'jane_smith',
                'email' => 'jane.smith@example.com',
                'password_hash' => '$2y$12$ceU76Z35h5Hzp2FAaKQWq.gk.ad7SSK4hFGzht8g7vH4i.qzaWBOO',
                'role' => 'user',
                'is_active' => 1,
                'created_at' => '2025-09-18 15:33:50',
                'updated_at' => '2025-09-18 15:34:41',
                'last_login' => '2025-09-18 15:34:41'
            ]
        ];

        foreach ($users as $user) {
            \DB::table('users')->updateOrInsert(
                ['id' => $user['id']],
                $user
            );
        }
    }
}
