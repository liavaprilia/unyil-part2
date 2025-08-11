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
        Schema::table('users', function (Blueprint $table) {
            $table->string('shipping_recipient_name')->nullable();
            $table->string('shipping_phone')->nullable();
            $table->string('shipping_address', 500)->nullable();
            $table->string('shipping_postal_code', 20)->nullable();
            $table->string('shipping_province')->nullable();
            $table->string('shipping_city')->nullable();
            $table->string('shipping_district')->nullable();
            $table->string('shipping_subdistrict')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'shipping_recipient_name',
                'shipping_phone',
                'shipping_address',
                'shipping_postal_code',
                'shipping_province',
                'shipping_city',
                'shipping_district',
                'shipping_subdistrict',
            ]);
        });
    }
};

