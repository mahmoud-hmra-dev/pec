<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('program_form_entries', function (Blueprint $table) {
            $table->id();

            // يمكنك أيضًا استخدام foreignId لتقليل الضوضاء
            $table->unsignedBigInteger('program_form_field_id');
            $table->unsignedBigInteger('sub_program_patient_id');

            $table->text('value')->nullable();
            $table->timestamps();

            // مفاتيح خارجية (أسماؤها لا تتجاوز 64 عادةً، إن أردت تقصيرها يمكنك تسميتها يدويًا أيضًا)
            $table->foreign('program_form_field_id', 'pfe_field_fk')
                  ->references('id')->on('program_form_fields')
                  ->onDelete('cascade');

            $table->foreign('sub_program_patient_id', 'pfe_subpp_fk')
                  ->references('id')->on('sub_program_patients')
                  ->onDelete('cascade');

            // مفتاح UNIQUE مركّب باسم قصير
            $table->unique(['program_form_field_id', 'sub_program_patient_id'], 'pfe_field_subpp_uq');
            // الاسم 'pfe_field_subpp_uq' أقل بكثير من 64 حرفًا
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('program_form_entries');
    }
};
