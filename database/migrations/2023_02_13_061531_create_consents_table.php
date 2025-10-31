<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConsentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consents', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_consent')->default(0);
            $table->foreignId('client_id')->nullable()->references('id')->on('clients')->nullOnDelete();
            $table->foreignId('program_id')->nullable()->references('id')->on('programs')->nullOnDelete();
            $table->foreignId('patient_id')->references("id")->on('patients')->cascadeOnDelete();
            $table->string('first_name')->nullable();
            $table->string('family_name')->nullable();
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
        Schema::dropIfExists('consents');
    }
}
