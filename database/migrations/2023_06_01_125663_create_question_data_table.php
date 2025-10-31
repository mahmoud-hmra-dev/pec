<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('question_data', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('visit_id')->nullable();
            $table->foreign('visit_id')->nullable()->references('id')->on('visits')->onDelete('cascade');
            $table->unsignedBigInteger('question_id')->nullable();
            $table->foreign('question_id')->nullable()->references('id')->on('questions')->onDelete('cascade');
            $table->text('content')->nullable();
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
        Schema::dropIfExists('question_data');
    }
}
