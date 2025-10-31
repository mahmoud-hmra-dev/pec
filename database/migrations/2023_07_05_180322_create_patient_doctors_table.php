<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientDoctorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patient_doctors', function (Blueprint $table) {
            $table->id();
            $table->boolean('isActive')->nullable();
            $table->unsignedBigInteger('patient_id')->nullable();
            $table->foreign('patient_id')->nullable()->references('id')->on('patients')->onDelete('cascade');
            $table->unsignedBigInteger('doctor_id')->nullable();
            $table->foreign('doctor_id')->nullable()->references('id')->on('doctors')->onDelete('SET NULL');
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
        Schema::dropIfExists('patient_doctors');
    }
}
