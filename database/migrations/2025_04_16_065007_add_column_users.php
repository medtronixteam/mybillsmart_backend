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
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('subscription_id')->nullable();
            $table->unsignedBigInteger('growth_subscription_id')->nullable();
            $table->foreign('subscription_id')->references('id')->on('subscriptions')->onDelete('cascade');
            $table->foreign('growth_subscription_id')->references('id')->on('subscriptions')->onDelete('cascade');
            $table->string('plan_name')->nullable();
            $table->double('euro_per_points')->default(1);
            $table->string('plan_growth_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('plan_name');
            $table->dropColumn('euro_per_points');
            $table->dropColumn('plan_growth_name');
            $table->dropColumn('subscription_id');
            $table->dropColumn('growth_subscription_id');
        });
    }
};
