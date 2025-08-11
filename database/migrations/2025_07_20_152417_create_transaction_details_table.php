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
        Schema::create('transaction_details', function (Blueprint $table) {
            $table->uuid('id_transaction_detail')->primary();
            $table->foreignUuid('tdtransaction_id')->constrained('transactions', 'id_transaction')->onDelete('cascade');
            $table->string('tdproduct_name');
            $table->decimal('tdproduct_price', 15, 2);
            $table->text('tdproduct_desc');
            $table->integer('tdproduct_qty');
            $table->string('tdproduct_img');
            $table->decimal('tdproduct_weight', 15, 2);
            $table->decimal('tdproduct_total_weight', 15, 2)->default(0);
            $table->decimal('tdproduct_total_price', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_details');
    }
};
