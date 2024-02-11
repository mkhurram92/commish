<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ZeroTrailReasonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('zero_trail_reasons')->insert(array(['name'=>'In Arrears'],['name'=>'In Credit'],['name'=>'Not Due']));
        //
    }
}
