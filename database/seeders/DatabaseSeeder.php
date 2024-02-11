<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $user = new User();
        $user->fname = "Jone";
        $user->lname = "Doe";
        $user->username = "admin123";
        $user->email = "commish@ftcarabia.com";
        $user->password = Hash::make("admin123@");
        $user->role = "admin";
        $user->save();
        $this->call( DealMissingRefStatusSeeder::class);
       
    }
}
