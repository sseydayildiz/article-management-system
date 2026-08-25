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
        Schema::table('articles', function (Blueprint $table) {
            if (!Schema::hasColumn('articles', 'keywords')) {
                $table->string('keywords')->nullable()->after('title');
            }
            if (!Schema::hasColumn('articles', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('content');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            if (Schema::hasColumn('articles', 'keywords')) {
                $table->dropColumn('keywords');
            }
            if (Schema::hasColumn('articles', 'is_active')) {
                $table->dropColumn('is_active');
            }
        });
    }
};