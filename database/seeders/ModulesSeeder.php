<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModulesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('modules')->insert(array(['module_name'=>'contacts'],['module_name'=>'deals'],['module_name'=>'brokers'],
            ['module_name'=>'users'],['module_name'=>'abp'],['module_name'=>'contact-role'],
            ['module_name'=>'products'],
            ['module_name'=>'industries'],['module_name'=>'lenders'],['module_name'=>'expense-type'],['module_name'=>'services'],['module_name'=>'referral-sources'],['module_name'=>'processors'],['module_name'=>'settings']));
    }
}
