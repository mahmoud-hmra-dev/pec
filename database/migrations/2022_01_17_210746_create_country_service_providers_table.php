<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountryServiceProvidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('country_service_providers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('service_provider_type_id')->nullable();
            $table->foreign('service_provider_type_id')->nullable()->references('id')->on('service_provider_types')->onDelete('cascade');
            $table->unsignedBigInteger('country_id')->nullable();
            $table->foreign('country_id')->nullable()->references('id')->on('countries')->onDelete('cascade');
            $table->unsignedBigInteger('sub_program_id')->nullable();
            $table->foreign('sub_program_id')->nullable()->references('id')->on('sub_programs')->onDelete('cascade');
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
        Schema::dropIfExists('country_service_providers');
    }
}
