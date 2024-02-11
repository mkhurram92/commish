<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientRelationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_relations', function (Blueprint $table) {
            $table->id();
            $table->integer('client_id');
            $table->integer('relation_with');
            $table->integer('relation');
            $table->tinyInteger('mailout');
            $table->unique(['client_id','relation_with']);
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
        Schema::dropIfExists('client_relations');
    }
}
