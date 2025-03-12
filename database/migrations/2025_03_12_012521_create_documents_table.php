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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone');
            $table->text('address');
            $table->date('date_of_birth');
            $table->string('id_card_front')->nullable();
            $table->string('id_card_back')->nullable();
            $table->string('individual_or_company')->nullable();
            $table->string('bank_receipt')->nullable();
            $table->string('last_service_invoice')->nullable();
            $table->string('lease_agreement')->nullable();
            $table->string('bank_account_certificate')->nullable();
            $table->date('expiration_date');
            $table->foreignId('client_id')->constrained('users');
            $table->unsignedBigInteger('contract_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
