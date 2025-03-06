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
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->nullable();
            $table->unsignedBigInteger('client_id')->default(0);
            $table->string('invoice_id');
            $table->string('supplier_name');
            $table->decimal('fixed_monthly_charges', 10, 2);
            $table->decimal('price_per_kwh', 10, 4);
            $table->decimal('meter_rental', 10, 2);
            $table->decimal('tax_per_kwh', 10, 4);
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
