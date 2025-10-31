<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceProviderTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_provider_types', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('service_type_id')->nullable();
            $table->foreign('service_type_id')->nullable()->references('id')->on('service_types')->onDelete('cascade');
            $table->unsignedBigInteger('service_provider_id')->nullable();
            $table->foreign('service_provider_id')->nullable()->references('id')->on('service_providers')->onDelete('cascade');
            //$table->unique(['service_type_id', 'service_provider_id']);
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
        Schema::dropIfExists('service_provider_types');
    }
}
