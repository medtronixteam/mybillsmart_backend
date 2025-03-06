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
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->string('user_id')->foreign()->references('id')->on('users')->onDelete('cascade');
            $table->string('client_id')->default(0);
            $table->string('invoice_id');
            $table->string('Supplier_name');
            $table->string('fixed_monthly_charges');
            $table->string('price_per_kwh');
            $table->string('meter_rental');
            $table->string('tax_per_kwh');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};
