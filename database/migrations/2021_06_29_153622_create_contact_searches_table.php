<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactSearchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contact_searches', function (Blueprint $table) {
            $table->id();
            $table->string('search_for')->nullable();
            $table->string('individual')->nullable();
            $table->string('general_mail_out')->nullable();
            $table->string('trading')->nullable();
            $table->string('trust_name')->nullable();
            $table->string('surname')->nullable();
            $table->string('first_name')->nullable();
            $table->string('preferred_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('dob')->nullable();
            $table->string('role_title')->nullable();
            $table->string('role')->nullable();
            $table->string('entity_name')->nullable();
            $table->string('principle_contact')->nullable();
            $table->string('work_phone')->nullable();
            $table->string('home_phone')->nullable();
            $table->string('mobile_phone')->nullable();
            $table->string('fax')->nullable();
            $table->string('email')->nullable();
            $table->string('web')->nullable();
            $table->string('home')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('mail')->nullable();
            $table->string('mail_city')->nullable();
            $table->string('mail_state')->nullable();
            $table->string('mail_postal_code')->nullable();
            $table->string('client_industry')->nullable();
            $table->string('other_industry')->nullable();
            $table->text('note')->nullable();
            $table->string('referred_to_1')->nullable();
            $table->string('services_1')->nullable();
            $table->string('date_1')->nullable();
            $table->text('note_1')->nullable();
            $table->string('referred_to_2')->nullable();
            $table->string('services_2')->nullable();
            $table->string('date_2')->nullable();
            $table->text('note_2')->nullable();
            $table->string('acc_name')->nullable();
            $table->string('acc_no')->nullable();
            $table->string('bank')->nullable();
            $table->string('bsb')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contact_searches');
    }
}
