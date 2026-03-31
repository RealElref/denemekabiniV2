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
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE transactions MODIFY COLUMN type ENUM('purchase', 'referral', 'gift', 'refund', 'bonus', 'usage') NOT NULL DEFAULT 'purchase'");
    }

    public function down(): void
    {
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE transactions MODIFY COLUMN type ENUM('purchase', 'referral', 'gift', 'refund', 'bonus') NOT NULL DEFAULT 'purchase'");
    }
};
