<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('product_types')->insert([
            [
                'id'                      => 1,
                'title'                   => 'Book "War and Peace"',
                'price'                   => 100,
                'is_available_for_sale'   => true,
                'is_available_for_loan'   => false,
                'price_for_8_hours_loan'  => 10,
                'price_for_16_hours_loan' => 15,
                'price_for_24_hours_loan' => 20
            ],
            [
                'id'                      => 2,
                'title'                   => 'Book "Hyperion"',
                'price'                   => 200,
                'is_available_for_sale'   => true,
                'is_available_for_loan'   => true,
                'price_for_8_hours_loan'  => 40,
                'price_for_16_hours_loan' => 45,
                'price_for_24_hours_loan' => 60
            ],
            [
                'id'                      => 3,
                'title'                   => 'Book "Clean Code"',
                'price'                   => 0,
                'is_available_for_sale'   => false,
                'is_available_for_loan'   => true,
                'price_for_8_hours_loan'  => 44,
                'price_for_16_hours_loan' => 45,
                'price_for_24_hours_loan' => 46
            ]
        ],
        );
    }
}
