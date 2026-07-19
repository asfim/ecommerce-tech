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
        Schema::create('product_landing_pages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->boolean('is_active')->default(true);
            $table->string('meta_title')->nullable();
            $table->string('tagline')->nullable();
            $table->string('heading')->nullable();
            $table->text('description')->nullable();
            $table->string('offer_text')->nullable();
            $table->decimal('old_price', 12, 2)->nullable();
            $table->decimal('new_price', 12, 2)->nullable();
            $table->string('discount_text')->nullable();
            $table->string('stock_text')->nullable();
            $table->string('whatsapp_number')->nullable();
            $table->string('whatsapp_text')->nullable();
            $table->json('features')->nullable();
            $table->json('testimonials')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_landing_pages');
    }
};

