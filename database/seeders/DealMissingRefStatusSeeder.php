<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class DealMissingRefStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $statuses= array(  ['name'=>'New','status_code' => '00' , 'color' => 'badge badge--primary'],
                          ['name'=>'In Progress','status_code' => '01' , 'color' => 'badge badge--danger'],
                          ['name'=>'Completed','status_code' => '02','color' => 'badge badge--success']
                        );

        DB::table('deals_with_missing_refs_statuses')->insert($statuses);
        
    }
}
