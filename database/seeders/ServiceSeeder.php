<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Service::truncate();
        $service = [
            ['name' => 'Plugging', 'price' => '2000'],
            ['name' => 'Monitoring', 'price' => '5000'],
            ['name' => 'Parking', 'price' => '2500'],
        ];

        Service::insert($service);
    }
}
