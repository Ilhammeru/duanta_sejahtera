<?php

namespace Database\Seeders;

use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::truncate();
        $data = [
            [
                'name' => 'Admin',
                'slug' => 'admin',
                'created_at' => Carbon::now()
            ],
            [
                'name' => 'Marketing',
                'slug' => 'marketing',
                'created_at' => Carbon::now()
            ],
            [
                'name' => 'Staff',
                'slug' => 'staff',
                'created_at' => Carbon::now()
            ]
        ];

        Role::insert($data);
    }
}
