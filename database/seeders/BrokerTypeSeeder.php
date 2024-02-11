<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BrokerTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('broker_types')->insert(array(['name'=>'Broker'],['name'=>'Inactive Broker'],['name'=>'Potential Broker']));
    }
}
