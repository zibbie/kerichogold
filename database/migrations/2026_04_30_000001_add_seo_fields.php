<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'slug')) {
                $table->string('slug')->unique()->nullable()->after('name');
            }
            if (!Schema::hasColumn('products', 'meta_title')) {
                $table->string('meta_title', 120)->nullable()->after('description');
                $table->text('meta_description')->nullable()->after('meta_title');
                $table->text('meta_keywords')->nullable()->after('meta_description');
                $table->string('canonical_url')->nullable()->after('meta_keywords');
                $table->string('og_image')->nullable()->after('canonical_url');
            }
        });

        Schema::table('categories', function (Blueprint $table) {
            if (!Schema::hasColumn('categories', 'meta_title')) {
                $table->string('meta_title', 120)->nullable()->after('description');
                $table->text('meta_description')->nullable()->after('meta_title');
                $table->text('meta_keywords')->nullable()->after('meta_description');
            }
        });

        Schema::table('pages', function (Blueprint $table) {
            if (!Schema::hasColumn('pages', 'meta_title')) {
                $table->string('meta_title', 120)->nullable()->after('content');
                $table->text('meta_description')->nullable()->after('meta_title');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['slug', 'meta_title', 'meta_description', 'meta_keywords', 'canonical_url', 'og_image']);
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['meta_title', 'meta_description', 'meta_keywords']);
        });

        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn(['meta_title', 'meta_description']);
        });
    }
};
