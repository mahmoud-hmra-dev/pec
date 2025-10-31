<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientMedicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_medications', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->unsignedBigInteger('client_id')->nullable();
            $table->foreign('client_id')->nullable()->references('id')->on('clients')->onDelete('cascade');
            $table->string('api_name')->nullable();
            $table->string('drug_initial')->nullable();

            $table->unsignedBigInteger('medication_type_id')->nullable();
            $table->foreign('medication_type_id')->nullable()->references('id')->on('medication_types')->onDelete('cascade');

            $table->unsignedBigInteger('service_provider_type_id')->nullable();
            $table->foreign('service_provider_type_id')->nullable()->references('id')->on('service_provider_types')->onDelete('cascade');
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
        Schema::dropIfExists('client_medications');
    }
}
