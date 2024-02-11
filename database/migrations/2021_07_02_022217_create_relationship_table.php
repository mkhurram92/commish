<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRelationshipTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('relationship', function (Blueprint $table) {
            $table->id();
            $table->string('name');
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
        Schema::dropIfExists('relationship');
    }
}

/*
  INSERT INTO `relationship` (`id`, `name`, `created_at`, `updated_at`) VALUES (NULL, 'Father', NULL, NULL), (NULL, 'Mother', NULL, NULL);

INSERT INTO `relationship` (`id`, `name`, `created_at`, `updated_at`) VALUES (NULL, 'Brother', NULL, NULL), (NULL,
'Son', NULL, NULL), (NULL,
'Daughter', NULL, NULL);

 * */
