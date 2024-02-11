<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CertificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('certifications')->insert(array(['name'=>'COSL'],['name'=>'FBAA'],['name'=>'FMA Review'],['name'=>'MFAA'],['name'=>'PI']));
    }
}
