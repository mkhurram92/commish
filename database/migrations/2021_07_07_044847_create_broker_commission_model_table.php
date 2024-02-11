<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBrokerCommissionModelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('broker_commission_model', function (Blueprint $table) {
            $table->id();
            $table->integer('broker_id');
            $table->integer('commission_model_id');
            $table->decimal('upfront_per',10,2);
            $table->decimal('trail_per',10,2);
            $table->decimal('flat_fee_chrg',10,2);
            $table->decimal('bdm_flat_fee_per',10,2);
            $table->decimal('bdm_upfront_per',10,2);
            $table->unique(['broker_id','commission_model_id'],'broker_commision_model');
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
        Schema::dropIfExists('broker_commission_model');
    }
}
