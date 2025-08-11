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
        Schema::table('transactions', function (Blueprint $table) {
            $table->decimal('total_discount', 15, 2)->after('total_price')->default(0);
            $table->renameColumn('transaction_seller_id', 'transaction_updated_by')->nullable()->comment('ID of the user who last updated the transaction');
        });

        // transaction_updated_by nullable
        Schema::table('transactions', function (Blueprint $table) {
            $table->unsignedBigInteger('transaction_updated_by')->nullable()->change();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->renameColumn('product_user_id', 'product_updated_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            //
            $table->dropColumn('total_discount');
            $table->renameColumn('transaction_updated_by', 'transaction_seller_id')->nullable();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->renameColumn('product_updated_by', 'product_user_id');
        });
    }
};
