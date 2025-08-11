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
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('id_transaction')->primary();
            $table->foreignId('transaction_user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('transaction_seller_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->enum('transaction_status', ['processed', 'shipped', 'completed', 'cancelled']);
            $table->text('transaction_note')->nullable();
            $table->string('transaction_pay_method');
            $table->string('transaction_pay_proof');
            $table->string('tshipping_proof')->nullable();
            $table->string('tshipping_tracking_number')->nullable();
            $table->string('tshipping_method');
            $table->string('tshipping_receipt_name');
            $table->string('tshipping_phone');
            $table->string('tshipping_country');
            $table->text('tshipping_address');
            $table->string('tshipping_zip_code');
            $table->string('tshipping_provience');
            $table->string('tshipping_city');
            $table->string('tshipping_district');
            $table->string('tshipping_subdistrict');
            $table->decimal('total_weight', 15, 2)->default(0);
            $table->decimal('tshipping_price', 15, 2)->default(0);
            $table->decimal('subtotal_price', 15, 2)->default(0);
            $table->decimal('total_price', 15, 2)->default(0);
            $table->integer('total_quantity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
