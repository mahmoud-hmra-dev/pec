<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientCountryProvidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patient_country_providers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sub_program_patient_id')->nullable();
            $table->foreign('sub_program_patient_id')->nullable()->references('id')->on('sub_program_patients')->onDelete('cascade');
            $table->unsignedBigInteger('country_service_provider_id')->nullable();
            $table->foreign('country_service_provider_id')->nullable()->references('id')->on('country_service_providers')->onDelete('cascade');
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
        Schema::dropIfExists('patient_country_providers');
    }
}
