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
        Schema::table('orders', function (Blueprint $blueprint) {
            $blueprint->string('email')->nullable()->after('user_id');
            $blueprint->string('name')->nullable()->after('email');
            $blueprint->string('phone')->nullable()->after('name');
            $blueprint->string('city')->nullable()->after('phone');
            $blueprint->string('zip')->nullable()->after('city');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $blueprint) {
            $blueprint->dropColumn(['email', 'name', 'phone', 'city', 'zip']);
        });
    }
};
