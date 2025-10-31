<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceProvidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_providers', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->nullable()->references('id')->on('users')->onDelete('cascade');

            $table->string('contract_type')->nullable();
            $table->float('contract_rate_price')->nullable();
            $table->string('contract_rate_price_per')->nullable();

            $table->string('attach_contract')->nullable();
            $table->string('attach_cv')->nullable();
            $table->string('city')->nullable();

            $table->unsignedBigInteger('country_id')->nullable();
            $table->foreign('country_id')->nullable()->references('id')->on('countries')->onDelete('SET NULL');
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
        Schema::dropIfExists('service_providers');
    }
}
