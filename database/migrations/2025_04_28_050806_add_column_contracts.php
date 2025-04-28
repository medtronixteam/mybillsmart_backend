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
        Schema::table('contracts', function (Blueprint $table) {
            if (!Schema::hasColumn('contracts', 'agreement_id')) {
                $table->unsignedBigInteger('agreement_id')->nullable()->after('id');
                $table->foreign('agreement_id')->references('id')->on('users')->onDelete('cascade');
            }

            if (!Schema::hasColumn('contracts', 'note')) {
                $table->fullText('note')->nullable();
            }


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->dropForeign(['agreement_id']);
            $table->dropColumn('agreement_id');
            $table->dropColumn('note');
        });
    }
};
