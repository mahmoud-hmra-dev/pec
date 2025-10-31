<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->text('question');
            $table->unsignedBigInteger('question_type_id')->nullable();
            $table->foreign('question_type_id')->nullable()->references('id')->on('question_types')->onDelete('SET NULL');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->foreign('category_id')->nullable()->references('id')->on('question_categories')->onDelete('SET NULL');
            $table->unsignedBigInteger('sub_program_id')->nullable();
            $table->foreign('sub_program_id')->nullable()->references('id')->on('sub_programs')->onDelete('cascade');
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
        Schema::dropIfExists('questions');
    }
}
