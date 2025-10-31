<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVisitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sub_program_patient_id')->nullable();
            $table->foreign('sub_program_patient_id')->nullable()->references('id')->on('sub_program_patients')->onDelete('cascade');
            $table->unsignedBigInteger('sub_program_id')->nullable();
            $table->foreign('sub_program_id')->nullable()->references('id')->on('sub_programs')->onDelete('cascade');
            $table->unsignedBigInteger('activity_type_id')->nullable();
            $table->foreign('activity_type_id')->nullable()->references('id')->on('activity_types')->onDelete('cascade');
            $table->unsignedBigInteger('service_provider_type_id')->nullable();
            $table->foreign('service_provider_type_id')->nullable()->references('id')->on('service_provider_types')->onDelete('cascade');
            $table->dateTime('start_at')->nullable();
            $table->dateTime('should_start_at')->nullable();
            $table->string('type_visit')->nullable();
            $table->text('meeting')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('visits');
    }
}
