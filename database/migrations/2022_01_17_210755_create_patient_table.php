<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('patient_no')->nullable();
            $table->date('birth_of_date')->nullable();
            $table->float('height')->nullable();
            $table->float('weight')->nullable();
            $table->string('BMI')->nullable();
            $table->boolean('is_over_weight')->nullable();
            $table->string('comorbidities')->nullable();
            $table->string('gender')->nullable();
            $table->boolean('is_eligible')->nullable();
            $table->string('pregnant')->nullable();
            $table->string('city')->nullable();
            $table->string('street')->nullable();
            $table->string('address')->nullable();
            $table->string('reporter_name')->nullable();
            $table->unsignedBigInteger('hospital_id')->nullable();
            $table->foreign('hospital_id')->nullable()->references('id')->on('hospitals')->onDelete('SET NULL');
            $table->unsignedBigInteger('pharmacy_id')->nullable();
            $table->foreign('pharmacy_id')->nullable()->references('id')->on('pharmacies')->onDelete('SET NULL');

            $table->string('discuss_by')->nullable();

            $table->string('is_eligible_document')->nullable();
            $table->string('mc_chronic_diseases')->nullable();
            $table->string('mc_medications')->nullable();
            $table->string('mc_surgeries')->nullable();
            $table->string('fmc_chronic_diseases')->nullable();
            $table->text('is_not_eligible')->nullable();
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
        Schema::dropIfExists('patients');
    }
}
