<?php

namespace Database\Seeders;


use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Bank;
class BankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $banks = [
            [
                'bank_name' => 'Bank 1',
                'account_name' => 'Account 1',
                'account_number' => '123456789',
                'image' => 'bank1.jpg',
            ],
            [
                'bank_name' => 'Bank 2',
                'account_name' => 'Account 2',
                'account_number' => '987654321',
                'image' => 'bank2.jpg',
            ],
            [
                'bank_name' => 'Bank 3',
                'account_name' => 'Account 3',
                'account_number' => '555555555',
                'image' => 'bank3.jpg',
            ],


        ];

        foreach ($banks as $key => $value) {

            Bank::create($value);
        }
    }
}
