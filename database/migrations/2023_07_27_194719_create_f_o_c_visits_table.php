<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFOCVisitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('f_o_c_visits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sub_program_patient_id')->nullable();
            $table->foreign('sub_program_patient_id')->nullable()->references('id')->on('sub_program_patients')->onDelete('cascade');
            $table->unsignedBigInteger('sub_program_id')->nullable();
            $table->foreign('sub_program_id')->nullable()->references('id')->on('sub_programs')->onDelete('cascade');
            $table->unsignedBigInteger('service_provider_type_id')->nullable();
            $table->foreign('service_provider_type_id')->nullable()->references('id')->on('service_provider_types')->onDelete('cascade');

            $table->string('site_notified')->nullable();
            $table->string('notification_method')->nullable();
            $table->string('collected_from_pharmacy')->nullable();
            $table->string('warehouse_call')->nullable();
            $table->string('attachment')->nullable();
            $table->dateTime('start_at')->nullable();
            $table->dateTime('reminder_at')->nullable();
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
        Schema::dropIfExists('f_o_c_visits');
    }
}
