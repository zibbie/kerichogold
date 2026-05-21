<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bot_visits', function (Blueprint $table) {
            $table->id();
            $table->string('bot_name', 50);
            $table->string('url', 500);
            $table->smallInteger('status_code');
            $table->integer('response_time_ms');
            $table->string('user_agent', 500)->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index('bot_name');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bot_visits');
    }
};
