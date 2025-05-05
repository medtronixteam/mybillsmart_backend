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
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn('price');
            $table->dropColumn('invoices');
            $table->dropColumn('agents');

            $table->bigInteger('annual_price')->default(0);
            $table->bigInteger('monthly_price')->default(0);
            $table->integer('invoices_per_month')->default(0);
            $table->integer('agents_per_month')->default(0);
            $table->string('type',20)->default('plan');


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->dropIfExists([
                'annual_price',
                'monthly_price',
                'invoices_per_month',
                'agents_per_month',
                'type'
            ]);
        });
    }
};
