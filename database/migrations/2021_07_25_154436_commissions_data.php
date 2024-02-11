<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CommissionsData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commissions_data', function (Blueprint $table) {
            $table->id();
            $table->string('loan_writer');
            $table->string('line_of_business');
            $table->string('commission_type');
            $table->string('funder');
            $table->string('client');
            $table->string('account_number');
            $table->string('period');
            $table->string('loan_amount');
            $table->string('rate');
            $table->string('commission');
            $table->string('gst');
            $table->string('total_paid');
            $table->string('referrer');
            $table->string('settlement_date');
            $table->string('payment_no');
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
        //
    }
}
