<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProcessorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('processors')->insert(array(['name'=>'Jason'],['name'=>'Angelo'],['name'=>'Trevor'],['name'=>'Antonella'],['name'=>'Kay'],['name'=>'Jack'],['name'=>'Todd']));
    }
}
