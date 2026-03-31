<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('domains', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('domain_name');
            $table->string('tld');
            $table->unsignedInteger('registration_years')->default(1);
            $table->unsignedInteger('credits_used');
            $table->unsignedBigInteger('price_paid')->nullable();
            $table->enum('status', [
                'pending',
                'approved',
                'active',
                'rejected',
                'expired',
                'cancelled',
            ])->default('pending');
            $table->text('admin_note')->nullable();
            $table->timestamp('registered_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('domains');
    }
};