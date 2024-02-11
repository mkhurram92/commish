<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExpenseTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('expense_types')->insert(array(['name'=>'Application Fee'],['name'=>'Building Inspection fee'],['name'=>'Business Cards'],['name'=>'Calculator'],['name'=>'Claw Back'],['name'=>'Courier'],['name'=>'CRAA'],['name'=>'Documentation Fee'],['name'=>'Esanda'],['name'=>'Fast Track'],['name'=>'Flat Fee'],['name'=>'GST'],['name'=>'Internet'],['name'=>'Legal Fees'],['name'=>'Loan Repayment'],['name'=>'Melbourne Cup'],['name'=>'Messenger'],['name'=>'Outstanding Commission'],['name'=>'Outstanding Expenses'],['name'=>'Overpayment'],['name'=>'P & I'],['name'=>'Property Assist'],['name'=>'Small Business Awards'],['name'=>'Software Fee'],['name'=>'Stationery'],['name'=>'Training'],['name'=>'Travel'],['name'=>'Valuation fee'],['name'=>'VSR'],['name'=>'Web Fee'],['name'=>'Website'],['name'=>'Yellow Pages']));
    }
}
