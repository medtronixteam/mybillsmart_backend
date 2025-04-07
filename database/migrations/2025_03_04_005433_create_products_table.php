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
        Schema::dropIfExists('products');
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_name');
            $table->unsignedBigInteger('group_id');
            $table->unsignedBigInteger('addedby_id');
            $table->string('light_category')->nullable();
            $table->decimal('fixed_rate', 10, 2)->default(0);
            $table->decimal('rl1', 10, 2)->nullable();
            $table->decimal('rl2', 10, 2)->nullable();
            $table->decimal('rl3', 10, 2)->nullable();
            $table->decimal('p1', 10, 2)->nullable();
            $table->decimal('p2', 10, 2)->nullable();
            $table->decimal('p3', 10, 2)->nullable();
            $table->decimal('p4', 10, 2)->nullable();
            $table->decimal('p5', 10, 2)->nullable();
            $table->decimal('p6', 10, 2)->nullable();
            $table->date('discount_period_start')->nullable();
            $table->date('discount_period_end')->nullable();
            $table->decimal('meter_rental', 10, 2)->default(0);
            $table->decimal('sales_commission', 10, 2)->default(0);
            $table->timestamps();
            $table->foreign('group_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('addedby_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
