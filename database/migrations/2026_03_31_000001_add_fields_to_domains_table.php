<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('domains', function (Blueprint $table) {
            $table->string('api_key')->unique()->nullable()->after('admin_note');
            $table->unsignedInteger('daily_limit')->default(100)->after('api_key');
            $table->unsignedInteger('total_requests')->default(0)->after('daily_limit');
            $table->timestamp('last_request_at')->nullable()->after('total_requests');
        });
    }

    public function down(): void
    {
        Schema::table('domains', function (Blueprint $table) {
            $table->dropColumn(['api_key', 'daily_limit', 'total_requests', 'last_request_at']);
        });
    }
};
