<?php

namespace Database\Seeders;

use App\Models\Division;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DivisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Division::truncate();

        $data = [
            [
                'name' => 'HRD'
            ],
            [
                'name' => 'Accounting'
            ],
            [
                'name' => 'Staff'
            ],
            [
                'name' => 'Manager'
            ],
            [
                'name' => 'Supervisor'
            ],
            [
                'name' => 'Marketing'
            ],
        ];
        Division::insert($data);
    }
}
