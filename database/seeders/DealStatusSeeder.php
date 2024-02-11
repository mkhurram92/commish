<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DealStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('deal_statuses')->insert(array(['name'=>'Application','status_code' => '00'],['name'=>'Submitted','status_code' => '01'],['name'=>'Conditional','status_code' => '02'],['name'=>'Formal','status_code' => '03'],['name'=>'Settled','status_code' => '04'],['name'=>'DNP','status_code' => '05'],['name'=>'Re-Financed','status_code' => '06'],['name'=>'Increase','status_code' => '07'],['name'=>'Discharged','status_code' => '08'],['name'=>'Enquiry','status_code' => '09'],['name'=>'Lead','status_code' => '10'],['name'=>'Approved in Principle','status_code' => '11']));

    }
}
