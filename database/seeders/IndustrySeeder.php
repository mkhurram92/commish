<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IndustrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('industries')->insert(array(['name'=>'Accountant'],['name'=>'Builder'],['name'=>'Lawyer'],['name'=>'Conveyancer/Lanbroker'],['name'=>'Electrician'],['name'=>'Plumber'],['name'=>'Statnionary'],['name'=>'Restaurant'],['name'=>'Auto Electrician'],['name'=>'Motor Mechanic'],['name'=>'Vehicle Salesman'],['name'=>'Mortgage Brokers'],['name'=>'Real Estate Agent'],['name'=>'Financial Planner'],['name'=>'Concreter'],['name'=>'Towing Services'],['name'=>'Engineer'],['name'=>'Property Developer'],['name'=>'JD - High End'],['name'=>'Insurance'],['name'=>'Investor'],['name'=>'Landlord'],['name'=>'Retired'],['name'=>'Self Employed'],['name'=>'Education'],['name'=>'Public Servant'],['name'=>'Other']));
    }
}
