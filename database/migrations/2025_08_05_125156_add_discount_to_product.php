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
            $table->decimal('product_discount', 15, 2)->after('product_price')->comment('Discount in price');
        });

        if(Schema::hasColumn('transaction_details', 'tdproduct_total_price')) {
            Schema::table('transaction_details', function (Blueprint $table) {
                $table->dropColumn('tdproduct_total_price');
            });
        }
        // transacstion details
        Schema::table('transaction_details', function (Blueprint $table) {
            $table->decimal('tdproduct_discount', 15, 2)->default(0)->after('tdproduct_price')->comment('Discount in price');
            $table->decimal('tdproduct_total_discount', 15, 2)->default(0)->after('tdproduct_discount')->comment('Total discount');
            $table->decimal('tdproduct_total_price', 15, 2)->default(0)->after('tdproduct_total_discount')->comment('Total price after discount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if(Schema::hasColumn('products', 'product_discount')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('product_discount');
            });
        }

        if(Schema::hasColumn('transaction_details', 'tdproduct_discount')) {
            Schema::table('transaction_details', function (Blueprint $table) {
                $table->dropColumn(['tdproduct_discount', 'tdproduct_total_discount', 'tdproduct_total_price']);
            });
        }
    }
};
