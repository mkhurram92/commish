<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReferralSourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('referral_sources')->insert(array(['name'=>'Referral Source'],['name'=>'Asset Mutual'],['name'=>'Ben Semple'],['name'=>'Bruno Zinghini'],['name'=>'Business Interests'],['name'=>'Carlo Lorefice'],['name'=>'Carmel Surace'],['name'=>'Cassandra Clements'],['name'=>'Chris Buchanon'],['name'=>'Client referral'],['name'=>'CMA Chartered Master Accountants'],['name'=>'Craig Cowling'],['name'=>'Daniel Harris- Brock'],['name'=>'Darren Duggan'],['name'=>'Darren Sherriff- Brock PA'],['name'=>'Duncan, John'],['name'=>'Existing Client'],['name'=>'Existing Client - Legendary Finance (New Lending)'],['name'=>'Facebook'],['name'=>'FMA Capital'],['name'=>'Friend/Family'],['name'=>'Gardiner Hall & Co'],['name'=>'Graham Colman'],['name'=>'HashChing'],['name'=>'Heard Financial'],['name'=>'Jack Chappel'],['name'=>'James Gibbs'],['name'=>'Jason Di Iulio'],['name'=>'Joe Mastrantuone'],['name'=>'John Keflianos'],['name'=>'John Russo'],['name'=>'John Zanon'],['name'=>'Justin Turci- Brock'],['name'=>'Khurram Mirza'],['name'=>'Kristy-Ann Falcione- Brock PA'],['name'=>'Laxman Mahra'],['name'=>'Le Cornu, Lewis & Hancock'],['name'=>'Legendary Finance Pty Ltd -Refinance from old book'],['name'=>'Lia Bonfiglio'],['name'=>'Lyndon Holland'],['name'=>'Maria Salegio'],['name'=>'Mark Dickson'],['name'=>'Mark Vandy- Brock PA'],['name'=>'Matt Watts'],['name'=>'Maximum Wealth Advisers'],['name'=>'Messenger Zerner'],['name'=>'MG'],['name'=>'mgi'],['name'=>'MGI Accountants'],['name'=>'Mina Meawad'],['name'=>'Murphy Financial Services'],['name'=>'Murphy FS'],['name'=>'MutualRewards'],['name'=>'Nick Georgeopoulos'],['name'=>'Nina Morelli'],['name'=>'Nina Mortimer- Brock'],['name'=>'Pam Hunt- Brock'],['name'=>'Pat Cheetham'],['name'=>'Paul Maio'],['name'=>'Peter Axon- Brock'],['name'=>'Pro Corp'],['name'=>'Robins Guthrie Taylor'],['name'=>'Samantha Williams- Brock'],['name'=>'SGIC'],['name'=>'Social Media'],['name'=>'Social Media / Online'],['name'=>'Solid Finance - Fred Morelli'],['name'=>'Solid Finance - Nina Morelli'],['name'=>'Staff Referral'],['name'=>'Steve Tsimopoulos'],['name'=>'Terry Antoniou'],['name'=>'Tom Annetts'],['name'=>'Unique Autos'],['name'=>'Vic Aquaro'],['name'=>'Vin'],['name'=>'Vin Cobiac - APIC Nominees PL'],['name'=>'White Pages'],['name'=>'Yellow Pages'],['name'=>'Yes Finance Capital'],['name'=>'Zambo Imre']));
    }
}
