<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBrokersStaffsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /* Schema::create('brokers_staffs', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->integer('position');
            $table->string('phone','15')->nullable();
            $table->string('mobile','15')->nullable();
            $table->string('fax','15')->nullable();
            $table->string('email','255')->nullable();
            $table->date('dob')->nullable();
            $table->string('website')->nullable();
            $table->string('mail_address')->nullable();
            $table->integer('mail_state')->nullable();
            $table->integer('mail_city')->nullable();
            $table->integer('referred_by')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->softDeletes();
            $table->timestamps();
        }); */
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('brokers_staffs');
    }
}
