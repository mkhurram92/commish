<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDealsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deals', function (Blueprint $table) {
            $table->id();
            $table->integer('broker_id');
            $table->integer('broker_staff_id')->nullable();
            $table->integer('contact_id');
            $table->integer('product_id');
            $table->integer('lender_id');
            $table->tinyInteger('line_of_credit');
            $table->string('loan_ref')->nullable();
            $table->integer('linked_to')->nullable();
            $table->string('loan_repaid')->nullable();
            $table->integer('status');
            $table->integer('commission_model');
            $table->string('proposed_settlement')->nullable();
            $table->decimal('actual_loan',10,2)->nullable();
            $table->tinyInteger('exclude_from_tracking');
            $table->tinyInteger('gst_applies');
            $table->decimal('broker_est_upfront',10,2)->nullable();
            $table->decimal('broker_est_trail',10,2)->nullable();
            $table->decimal('broker_est_brokerage',10,2)->nullable();
            $table->decimal('broker_est_loan_amt',10,2)->nullable();
            $table->decimal('agg_est_upfront',10,2)->nullable();
            $table->decimal('agg_est_trail',10,2)->nullable();
            $table->decimal('agg_est_brokerage',10,2)->nullable();
            $table->tinyInteger('has_trail');
            $table->text('note')->nullable();
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
        Schema::dropIfExists('deals');
    }
}
