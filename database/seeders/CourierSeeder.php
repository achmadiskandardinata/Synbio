<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Courier;

class CourierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $couriers = [
            [
                'name' => 'Fulan',
                'service' => 'JNE',
                'cost' => 10000,
            ],
            [
                'name' => 'Fulan2',
                'service' => 'Grab',
                'cost' => 20000
            ],
        ];

        foreach ($couriers as $key => $value) {
            Courier::create($value);
        }
    }
}
