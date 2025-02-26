<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'title' => 'Banner 1',
                'slug' => 'banner-1',
                'description' => 'Description 1',
                'image' => 'banner1.jpg',
                'weight' => '1',
                'price' => '1000', //1000 gram = 1 kg
                'status' => 'show',
            ],
            [
                'title'=> 'Banner 2',
                'slug' => 'banner-2',
                'description' => 'Description 2',
                'image' => 'banner2.jpg',
                'weight' => '2',
                'price' => '2000', //2000 gram = 2 kg
                'status' => 'hide',
            ],
            [
                'title'=> 'Banner 3',
                'slug' => 'banner-3',
                'description' => 'Description 3',
                'image' => 'banner3.jpg',
                'weight' => '3',
                'price' => '3000', //3000 gram = 3 kg
                'status' => 'hide',
            ],
            [
                'title'=> 'Banner 4',
                'slug' => 'banner-4',
                'description' => 'Description 4',
                'image' => 'banner4.jpg',
                'weight' => '4',
                'price' => '4000', //4000 gram = 4 kg
                'status' => 'show',
            ],
            [
                'title'=> 'Banner 5',
                'slug' => 'banner-5',
                'description' => 'Description 5',
                'image' => 'banner5.jpg',
                'weight' => '5',
                'price' => '5000', //5000 gram = 5 kg
                'status' => 'hide',
            ],
        ];

        foreach($products as $key =>$value){
            Product::create($value);
        }
    }
}
