<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RefferorRelationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('refferor_relations')->insert(array(['name'=>'Accountant'],['name'=>'Business'],['name'=>'Friend']));
    }
}
