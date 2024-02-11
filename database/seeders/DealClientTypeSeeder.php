<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DealClientTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('deal_client_types')->insert(array(['name'=>'Primary Borrower'],['name'=>'Second Borrower'],['name'=>'Guarantor'],['name'=>'Third Borrower'],['name'=>'Fourth Borrower'],['name'=>'Fifth Borrower']));
    }
}
