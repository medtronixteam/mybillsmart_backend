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
        if (!Schema::hasTable('contracts')) { // ✅ yahan check lag gaya
            Schema::create('contracts', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('client_id')->default(0);
                $table->string('contracted_provider');
                $table->longText('note')->nullable();
                $table->decimal('contracted_rate', 8, 2);
                $table->date('closure_date');
                $table->date('start_date')->nullable();
                $table->string('status')->default('pending');
                
                $table->unsignedBigInteger('group_id');
                $table->foreign('group_id')->references('id')->on('users')->onDelete('cascade');

                $table->integer('agent_id')->default(0);
                
                $table->unsignedBigInteger('agreement_id');
                $table->foreign('agreement_id')->references('id')->on('agreements')->onDelete('cascade');

                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
