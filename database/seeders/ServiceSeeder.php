<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('services')->insert(array(['name'=>'Accountancy'],['name'=>'Conveyancing'],['name'=>'Financial Planning'],['name'=>'Insurance'],['name'=>'Legal Advice'],['name'=>'SGIC']));
    }
}
