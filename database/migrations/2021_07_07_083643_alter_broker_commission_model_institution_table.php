<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterBrokerCommissionModelInstitutionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('broker_commission_model_institution', function (Blueprint $table) {
            $table->integer('broker_id');
            $table->integer('broker_com_mo_inst_id');
            $table->integer('lender_id');
            $table->decimal('upfront',10,2);
            $table->decimal('trail',10,2);
            $table->unique(['broker_id','broker_com_mo_inst_id','lender_id'],'broker_comm_model_lender');
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();
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
