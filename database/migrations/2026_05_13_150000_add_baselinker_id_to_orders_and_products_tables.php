<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * BaseLinker Integration - Phase 0
     * Adds baselinker_id mapping column to orders and products tables
     * for 1:1 synchronization with BaseLinker order manager and inventory.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->bigInteger('baselinker_id')->nullable()->unique()->after('order_number');
            $table->string('baselinker_status')->nullable()->after('baselinker_id');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->bigInteger('baselinker_id')->nullable()->unique()->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['baselinker_id', 'baselinker_status']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('baselinker_id');
        });
    }
};
