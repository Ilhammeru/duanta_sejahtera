<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserRole;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();

        $phone = '085795327357';
        $user = [
            [
                'name' => 'Ilham Meru Gumilang',
                'email' => 'gumilang.dev@gmail.com',
                'password' => Hash::make('ilhammeru'),
                'phone' => $phone,
                'birth_date' => date('Y-m-d', strtotime('1996-05-24')),
                'date_in' => date('Y-m-d', strtotime('2021-01-01')),
                'division_id' => 5,
                'identity_number' => '3573042405960004',
                'status' => 1
            ],
            [
                'name' => 'admin',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('admin'),
                'phone' => (int)$phone,
                'birth_date' => date('Y-m-d', strtotime('1996-05-24')),
                'date_in' => date('Y-m-d', strtotime('2021-01-01')),
                'division_id' => 6,
                'identity_number' => '3573042405960004',
                'status' => 1
            ],
            [
                'name' => 'staff',
                'email' => 'staff@gmail.com',
                'password' => Hash::make('staff'),
                'phone' => $phone,
                'birth_date' => date('Y-m-d', strtotime('1996-05-24')),
                'date_in' => date('Y-m-d', strtotime('2021-01-01')),
                'division_id' => 3,
                'identity_number' => '3573042405960004',
                'status' => 1
            ]
        ];

        User::insert($user);

        $userRole = [
            [
                'user_id' => 1,
                'role_id' => 1
            ],
            [
                'user_id' => 2,
                'role_id' => 1
            ],
            [
                'user_id' => 3,
                'role_id' => 3
            ],
        ];

        UserRole::truncate();
        UserRole::insert($userRole);
    }
}
