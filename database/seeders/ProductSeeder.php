<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('products')->insert(array(['name'=>'Insurance'],['name'=>'Risk, Life & FP'],['name'=>'Other'],['name'=>'Chattel Finance'],['name'=>'Personal Loan'],['name'=>'Commercial Loan'],['name'=>'Residential Loan']));
    }
}
