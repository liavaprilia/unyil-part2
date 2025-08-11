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
        Schema::table('transaction_details', function (Blueprint $table) {
            //
            // $table->decimal('tdproduct_discount', 15, 2)->default(0)->after('tdproduct_price')->comment('Discount in price');
            // add tdproduct_total_discount
            // $table->decimal('tdproduct_total_discount', 15, 2)->default(0)->after('tdproduct_discount')->comment('Total discount for the product in the transaction');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaction_details', function (Blueprint $table) {
            //
            // $table->dropColumn('tdproduct_discount');
            // $table->dropColumn('tdproduct_total_discount');
        });
    }
};
