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
        Schema::create('carts', function (Blueprint $table) {
            $table->uuid('id_cart')->primary();
            $table->foreignId('cart_user_id')->constrained('users', 'id')->onDelete('cascade');
            $table->foreignUuid('cart_product_id')->constrained('products', 'id_product')->onDelete('cascade');
            $table->integer('cart_qty')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
