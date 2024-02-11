<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBrokerExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('broker_expenses', function (Blueprint $table) {
            $table->id();
            $table->integer('broker_id');
            $table->integer('expense_type_id');
            $table->date('ordered_date');
            $table->date('broker_charged');
            $table->date('broker_paid')->nullable();
            $table->text('detail');
            $table->decimal('base_cost',10,2);
            $table->decimal('markup',10,2);
            $table->decimal('broker_charge',10,2);
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('broker_expenses');
    }
}
