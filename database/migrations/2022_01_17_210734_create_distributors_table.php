<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDistributorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('distributors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('program_id')->nullable();
            $table->foreign('program_id')->nullable()->references('id')->on('programs')->onDelete('cascade');
            $table->string('contract_person')->nullable();
            $table->unsignedBigInteger('country_id')->nullable();
            $table->foreign('country_id')->nullable()->references('id')->on('countries')->onDelete('SET NULL');
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
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
        Schema::dropIfExists('distributors');
    }
}
