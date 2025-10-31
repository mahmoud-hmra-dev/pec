<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('program_form_fields', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('program_id');
            $table->string('label');
            $table->string('field_key');
            $table->string('field_type')->default('text');
            $table->boolean('is_required')->default(false);
            $table->unsignedInteger('display_order')->default(0);
            $table->json('options')->nullable();
            $table->text('help_text')->nullable();
            $table->timestamps();

            $table->unique(['program_id', 'field_key']);
            $table->foreign('program_id')->references('id')->on('programs')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('program_form_fields');
    }
};
