<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBrokerReferrorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('broker_referrors', function (Blueprint $table) {
            $table->id();
            $table->integer('referror');
            $table->string('entity')->nullable();
            $table->decimal('upfront',10,2)->nullable();
            $table->decimal('trail',10,2)->nullable();
            $table->decimal('comm_per_deal',10,2)->nullable();
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
        Schema::dropIfExists('broker_referrors');
    }
}
