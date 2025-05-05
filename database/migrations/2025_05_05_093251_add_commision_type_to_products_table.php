<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('commision_type')->default('percentage');
            $table->string('agreement_type')->default('both');
            $table->string('validity_period_from')->nullable();
            $table->string('validity_period_to')->nullable();
            $table->longText('contact_terms')->nullable();
            $table->string('contract_duration')->nullable();
            $table->string('customer_type')->default('business');
            $table->bigInteger('power_term')->nullable();
            $table->string('peak')->nullable();
            $table->string('off_peak')->nullable();
            $table->string('energy_term_by_time')->nullable();
            $table->string('variable_term_by_tariff')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            //
        });
    }
};
