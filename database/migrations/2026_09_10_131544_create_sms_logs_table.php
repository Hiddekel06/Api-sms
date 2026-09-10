<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sms_logs', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('api_token_id')->nullable()->constrained('api_tokens')->nullOnDelete();
            $table->string('source')->default('web'); // 'web' ou 'api'
            $table->string('type')->default('single'); // 'single' ou 'bulk'
            $table->string('recipient')->nullable();
            $table->unsignedInteger('recipient_count')->default(1);
            $table->text('message');
            $table->boolean('success');
            $table->string('message_id')->nullable();
            $table->json('raw_response')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sms_logs');
    }
};
