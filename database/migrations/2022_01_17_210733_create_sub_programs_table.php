<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubProgramsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_programs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('program_id')->nullable();
            $table->foreign('program_id')->nullable()->references('id')->on('programs')->onDelete('SET NULL');
            $table->unsignedBigInteger('country_id')->nullable();
            $table->foreign('country_id')->nullable()->references('id')->on('countries')->onDelete('SET NULL');
            $table->unsignedBigInteger('drug_id')->nullable();
            $table->foreign('drug_id')->nullable()->references('id')->on('drugs')->onDelete('SET NULL');
            $table->string('type')->nullable();
            $table->bigInteger('treatment_duration')->nullable();
            $table->bigInteger('target_number_of_patients')->nullable();
            $table->boolean('eligible')->nullable();
            $table->boolean('has_calls')->nullable();
            $table->boolean('has_visits')->nullable();
            $table->boolean('has_FOC')->nullable();
            $table->dateTime('start_date')->nullable();
            $table->dateTime('finish_date')->nullable();
            $table->string('program_initial')->nullable();
            $table->boolean('is_follow_program_date')->nullable();
            $table->bigInteger('visit_every_day')->nullable();
            $table->bigInteger('call_every_day')->nullable();
            $table->bigInteger('cycle_period')->nullable();
            $table->bigInteger('cycle_number')->nullable();
            $table->bigInteger('cycle_reminder_at')->nullable();
            $table->string('name')->nullable();
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
        Schema::dropIfExists('sub_programs');
    }
}
