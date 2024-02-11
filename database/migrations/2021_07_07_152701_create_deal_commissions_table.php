<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDealCommissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deal_commissions', function (Blueprint $table) {
            $table->id();
            $table->integer('deal_id');
            $table->integer('type');
            $table->decimal('total_amount',10,2);
            $table->date('date_statement')->nullable();
            $table->decimal('agg_amount',10,2)->nullable();
            $table->decimal('broker_amount',10,2)->nullable();
            $table->date('bro_amt_date_paid')->nullable();
            $table->decimal('referror_amount',10,2)->nullable();
            $table->date('ref_amt_date_paid')->nullable();
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();
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
        Schema::dropIfExists('deal_commissions');
    }
}
