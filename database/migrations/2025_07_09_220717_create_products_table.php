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
        Schema::create('products', function (Blueprint $table) {
            $table->uuid('id_product')->primary();
            $table->foreignId('product_user_id')->constrained('users', 'id')->onDelete('cascade');
            $table->string('product_name');
            $table->decimal('product_price', 15, 2);
            $table->text('product_desc')->nullable();
            $table->integer('product_stock')->default(0);
            $table->string('product_image')->nullable();
            $table->integer('product_weight')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
