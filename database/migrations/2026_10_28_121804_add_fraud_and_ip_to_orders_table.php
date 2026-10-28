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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('ip_address', 45)->nullable()->after('user_id')->index();
            $table->boolean('is_fraud')->default(false)->after('ip_address')->index();
            $table->string('fraud_reason')->nullable()->after('is_fraud');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['ip_address']);
            $table->dropIndex(['is_fraud']);
            $table->dropColumn(['ip_address', 'is_fraud', 'fraud_reason']);
        });
    }
};
