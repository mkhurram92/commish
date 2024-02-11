<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CommissionModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('commission_model')->insert(array(['name'=>'Fixed Rate'],['name'=>'Flat Fee'],['name'=>'Variable Rate']));
    }
}
