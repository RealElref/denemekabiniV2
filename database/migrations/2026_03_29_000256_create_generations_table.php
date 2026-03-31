<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('generations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('person_image_path');
            $table->string('garment_image_path');
            $table->string('garment_type')->default('upper_body');
            $table->string('wiro_job_id')->nullable()->unique();
            $table->string('result_image_path')->nullable();
            $table->string('result_image_url')->nullable();
            $table->boolean('has_watermark')->default(false);
            $table->enum('status', [
                'queued',
                'processing',
                'completed',
                'failed',
            ])->default('queued');
            $table->unsignedInteger('credits_used')->default(1);
            $table->text('error_message')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('generations');
    }
};