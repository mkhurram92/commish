<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBrokersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('brokers', function (Blueprint $table) {
            $table->id();
            $table->integer('type');
            $table->tinyInteger('is_individual');
            $table->string('trading')->nullable();
            $table->string('trust_name')->nullable();
            $table->string('salutation')->nullable();
            $table->string('surname')->nullable();
            $table->string('given_name')->nullable();
            $table->string('dob')->nullable();
            $table->string('entity_name')->nullable();
            $table->string('work_phone',20)->nullable();
            $table->string('home_phone',20)->nullable();
            $table->string('mobile_phone',20)->nullable();
            $table->string('fax',20)->nullable();
            $table->string('email',255)->nullable();
            $table->string('web',255)->nullable();
            $table->string('business',255)->nullable();
            $table->integer('state')->nullable();
            $table->integer('city')->nullable();
            $table->string('pincode',20)->nullable();
            $table->integer('bdm')->nullable();
            $table->tinyInteger('is_bdm')->nullable();
            $table->tinyInteger('subject_to_gst')->nullable();
            $table->string('account_name',255)->nullable();
            $table->string('account_number',255)->nullable();
            $table->string('bank',255)->nullable();
            $table->string('bsb',255)->nullable();
            $table->longText('note')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('brokers');
    }
}
