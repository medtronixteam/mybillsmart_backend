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
        Schema::table('offers', function (Blueprint $table) {
            $table->dropColumn(['fixed_monthly_charges', 'price_per_kwh']);
            $table->string('product_name')->nullable();
            $table->renameColumn('supplier_name', 'provider_name');
            $table->renameColumn('tax_per_kwh', 'sales_commission');
            $table->renameColumn('meter_rental', 'saving');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('offers', function (Blueprint $table) {
            $table->string('supplier_name')->nullable();
            $table->decimal('fixed_monthly_charges', 8, 2)->nullable();
            $table->renameColumn('sales_commission', 'meter_rental');
        });
    }
};
