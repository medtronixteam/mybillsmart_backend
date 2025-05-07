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
        Schema::table('payment_intents', function (Blueprint $table) {
            $table->string('plan_duration')->default('monthly');
        });
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->string('plan_duration')->default('monthly');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_intents', function (Blueprint $table) {
            $table->dropColumn('plan_duration');
        });
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn('plan_duration');
        });
    }
};
