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
            // Add agreement_id column if not exists
            if (!Schema::hasColumn('contracts', 'agreement_id')) {
                $table->unsignedBigInteger('agreement_id')->nullable()->after('id');
                $table->foreign('agreement_id')->references('id')->on('users')->onDelete('cascade');
            }

            // Add fulltext index on 'note' column if column exists
            if (Schema::hasColumn('contracts', 'note')) {
                $table->fullText('note');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            if (Schema::hasColumn('contracts', 'agreement_id')) {
                $table->dropForeign(['agreement_id']);
                $table->dropColumn('agreement_id');
            }
            if (Schema::hasColumn('contracts', 'note')) {
                // Drop fulltext index if exists
                $table->dropIndex(['note']); // drop fulltext index
            }
        });
    }
};
