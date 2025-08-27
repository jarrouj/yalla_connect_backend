<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('checkouts', function (Blueprint $table) {
            // make product optional
            $table->dropForeign(['product_id']);
            $table->unsignedBigInteger('product_id')->nullable()->change();
            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();

            // add specialty reference
            $table->foreignId('specialty_id')->nullable()->constrained()->cascadeOnDelete();

            // optional: totals if you want
            if (!Schema::hasColumn('checkouts', 'final_price')) {
                $table->decimal('final_price', 10, 2)->nullable()->after('price');
            }
            if (!Schema::hasColumn('checkouts', 'quantity')) {
                $table->unsignedInteger('quantity')->default(1)->after('final_price');
            }
            if (!Schema::hasColumn('checkouts', 'total_paid')) {
                $table->decimal('total_paid', 10, 2)->nullable()->after('quantity');
            }
            if (!Schema::hasColumn('checkouts', 'promo_code')) {
                $table->string('promo_code')->nullable()->after('total_paid');
            }
            if (!Schema::hasColumn('checkouts', 'promo_percent')) {
                $table->unsignedTinyInteger('promo_percent')->nullable()->after('promo_code');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('checkouts', function (Blueprint $table) {
            $table->dropForeign(['specialty_id']);
            $table->dropColumn('specialty_id');

            // make product_id required again (only if you really want to revert)
            $table->dropForeign(['product_id']);
            $table->unsignedBigInteger('product_id')->nullable(false)->change();
            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();

            // optional columns cleanup
            $table->dropColumn(['final_price','quantity','total_paid','promo_code','promo_percent']);
        });
    }
};
