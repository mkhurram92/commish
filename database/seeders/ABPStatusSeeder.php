<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ABPStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('abp_statuses')->insert(array(['name'=>'Potential ABP'],['name'=>'PI Expiry']));

        //
    }
}
