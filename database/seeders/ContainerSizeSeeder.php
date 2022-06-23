<?php

namespace Database\Seeders;

use App\Models\ContainerSizeType;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContainerSizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ContainerSizeType::truncate();
        $data = [
            ['size' => 20, 'type' => 'GP', 'created_at' => Carbon::now()],
            ['size' => 40, 'type' => 'GP', 'created_at' => Carbon::now()],
            ['size' => 40, 'type' => 'HC', 'created_at' => Carbon::now()],
            ['size' => 45, 'type' => 'HC', 'created_at' => Carbon::now()],
            ['size' => 20, 'type' => 'RF/RH', 'created_at' => Carbon::now()],
            ['size' => 40, 'type' => 'RF/RH', 'created_at' => Carbon::now()],
            ['size' => 20, 'type' => 'FR', 'created_at' => Carbon::now()],
            ['size' => 40, 'type' => 'HC', 'created_at' => Carbon::now()],
            ['size' => 20, 'type' => 'OT', 'created_at' => Carbon::now()],
            ['size' => 40, 'type' => 'HC', 'created_at' => Carbon::now()],
        ];

        ContainerSizeType::insert($data);
    }
}
