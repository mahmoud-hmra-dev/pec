<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProgramCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('program_countries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('program_id')->nullable();
            $table->foreign('program_id')->nullable()->references('id')->on('programs')->onDelete('cascade');
            $table->unsignedBigInteger('country_id')->nullable();
            $table->foreign('country_id')->nullable()->references('id')->on('countries')->onDelete('cascade');
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
        Schema::dropIfExists('program_countries');
    }
}
