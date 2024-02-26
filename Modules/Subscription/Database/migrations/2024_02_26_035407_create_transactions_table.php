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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscription_id')->constrained('subscriptions');
            $table->integer('eghl_transaction_status');
            $table->string('user_transaction_status')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('gateway_transaction_id')->nullable();
            $table->string('type')->nullable();
            $table->float('amount');
            $table->string('payment_method')->nullable();
            $table->unsignedInteger('invoice_no')->nullable();
            $table->string('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
