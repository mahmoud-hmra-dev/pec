<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('program_form_entries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('program_form_field_id');
            $table->unsignedBigInteger('sub_program_patient_id');
            $table->text('value')->nullable();
            $table->timestamps();

            $table->foreign('program_form_field_id')->references('id')->on('program_form_fields')->onDelete('cascade');
            $table->foreign('sub_program_patient_id')->references('id')->on('sub_program_patients')->onDelete('cascade');
            $table->unique(['program_form_field_id', 'sub_program_patient_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('program_form_entries');
    }
};
