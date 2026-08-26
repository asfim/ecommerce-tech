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
        Schema::table('products', function (Blueprint $table) {
            $table->index('category_id');
            $table->index('is_active');
            $table->index('is_featured');
            $table->index('is_new_arrival');
            $table->index('discount_type');
            $table->index('price');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->index('is_active');
            $table->index('is_trending');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['category_id']);
            $table->dropIndex(['is_active']);
            $table->dropIndex(['is_featured']);
            $table->dropIndex(['is_new_arrival']);
            $table->dropIndex(['discount_type']);
            $table->dropIndex(['price']);
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropIndex(['is_active']);
            $table->dropIndex(['is_trending']);
        });
    }
};
