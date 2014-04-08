<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCitiesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cities', function ($table)
        {
            $table->increments('id');
            $table->string('name');
            $table->string('state');
            $table->double('latitude', 3, 6);
            $table->double('longitude', 3, 6);
            $table->timestamps();
        });
        DB::statement('ALTER TABLE cities ADD location POINT');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cities');
    }

}
