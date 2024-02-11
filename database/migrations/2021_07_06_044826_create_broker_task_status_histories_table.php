<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBrokerTaskStatusHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('broker_task_status_histories', function (Blueprint $table) {
            $table->integer('broker_id');
            $table->integer('task_id');
            $table->date('change_date');

            $table->text('remark');
            $table->date('completed_date')->nullable();
            $table->integer('from_status');
            $table->integer('to_status');
            $table->integer('created_by');
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
        Schema::dropIfExists('broker_task_status_histories');
    }
}
