<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTreatmentDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('treatment_data', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('visit_id')->nullable();
            $table->foreign('visit_id')->nullable()->references('id')->on('visits')->onDelete('cascade');
            $table->string('current_dose_reached')->nullable(); // what id type of filed
            $table->string('dose_escalation')->nullable();      // what id type of filed
            $table->date('dose_date')->nullable();
            $table->date('side_effects')->nullable();
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
        Schema::dropIfExists('treatment_data');
    }
}
