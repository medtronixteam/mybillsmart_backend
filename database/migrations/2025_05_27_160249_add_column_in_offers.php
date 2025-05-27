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
            $table->dropColumn('saving');
            $table->double('monthly_saving_amount')->default(0);
            $table->double('yearly_saving_amount')->default(0);
            $table->double('yearly_saving_percentage')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('offers', function (Blueprint $table) {
            $table->dropColumn(['monthly_saving_amount', 'yearly_saving_amount', 'yearly_saving_percentage']);
        });
    }
};
