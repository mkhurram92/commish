<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('memberships')->insert(array(['module_name'=>'master'],['module_name'=>'settings'],['module_name'=>'contacts'],['module_name'=>'deals'],['module_name'=>'broker']));
    }
}
