<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('generations', function (Blueprint $table) {
            // Kaynak: dashboard kullanıcısı mı, embed widget mi
            $table->enum('source', ['dashboard', 'embed'])->default('dashboard')->after('user_id');

            // Embed widget'tan gelen istekler için hangi domain
            $table->unsignedBigInteger('domain_id')->nullable()->after('source');
            $table->foreign('domain_id')->references('id')->on('domains')->nullOnDelete();

            // Kıyafet URL'si (embed'den gelen harici URL)
            $table->string('garment_url')->nullable()->after('garment_image_path');

            // İşlem ilerleme durumu (0-100)
            $table->unsignedTinyInteger('progress')->default(0)->after('garment_url');

            // Zaman damgaları
            $table->timestamp('started_at')->nullable()->after('processed_at');
            $table->timestamp('completed_at')->nullable()->after('started_at');
        });
    }

    public function down(): void
    {
        Schema::table('generations', function (Blueprint $table) {
            $table->dropForeign(['domain_id']);
            $table->dropColumn([
                'source',
                'domain_id',
                'garment_url',
                'progress',
                'started_at',
                'completed_at',
            ]);
        });
    }
};
