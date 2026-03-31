<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('package_id')->nullable()->constrained()->nullOnDelete();
            $table->string('order_id')->unique();
            $table->string('pos_transaction_id')->nullable();
            $table->string('pos_payment_url')->nullable();
            $table->unsignedBigInteger('amount');
            $table->string('currency', 3)->default('TRY');
            $table->unsignedInteger('credit_amount');
            $table->enum('status', [
                'pending',
                'paid',
                'failed',
                'refunded',
                'cancelled',
            ])->default('pending');
            $table->enum('type', [
                'purchase',
                'referral',
                'gift',
                'refund',
                'bonus',
            ])->default('purchase');
            $table->text('note')->nullable();
            $table->json('pos_payload')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};