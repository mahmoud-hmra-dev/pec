<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhysicianDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('physician_data', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('visit_id')->nullable();
            $table->foreign('visit_id')->nullable()->references('id')->on('visits')->onDelete('cascade');
            $table->text('notes')->nullable();
            $table->text('other_medications')->nullable();
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
        Schema::dropIfExists('physician_data');
    }
}
