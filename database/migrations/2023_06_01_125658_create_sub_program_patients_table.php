<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubProgramPatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_program_patients', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_id')->nullable();
            $table->foreign('patient_id')->nullable()->references('id')->on('patients')->onDelete('cascade');
            $table->unsignedBigInteger('sub_program_id')->nullable();
            $table->foreign('sub_program_id')->nullable()->references('id')->on('sub_programs')->onDelete('SET NULL');
            $table->boolean('is_consents')->nullable();
            $table->string('consent_document')->nullable();
            $table->boolean('is_their_safety_report')->nullable();
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
        Schema::dropIfExists('sub_program_patients');
    }
}
