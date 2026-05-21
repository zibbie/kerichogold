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
        Schema::create('crawl_logs', function (Blueprint $table) {
            $table->id();
            $table->string('bot_name')->index();
            $table->text('url');
            $table->integer('status_code')->index();
            $table->string('ip_address');
            $table->text('user_agent');
            $table->float('response_time')->nullable();
            $table->timestamp('crawled_at')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crawl_logs');
    }
};
