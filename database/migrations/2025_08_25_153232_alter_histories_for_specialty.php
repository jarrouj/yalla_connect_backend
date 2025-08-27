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
        Schema::table('histories', function (Blueprint $table) {
            // drop existing FK on product_id, make it nullable, then re-add FK
            $table->dropForeign(['product_id']);
            $table->unsignedBigInteger('product_id')->nullable()->change();
            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();

            // add specialty_id (nullable) with FK
            if (!Schema::hasColumn('histories', 'specialty_id')) {
                $table->foreignId('specialty_id')->nullable()->constrained()->cascadeOnDelete();
            }

            // (optional) if you store amounts/qty in history
            if (!Schema::hasColumn('histories', 'amount')) {
                $table->decimal('amount', 10, 2)->nullable();
            }
            if (!Schema::hasColumn('histories', 'quantity')) {
                $table->unsignedInteger('quantity')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('histories', function (Blueprint $table) {
            if (Schema::hasColumn('histories', 'specialty_id')) {
                $table->dropForeign(['specialty_id']);
                $table->dropColumn('specialty_id');
            }

            $table->dropForeign(['product_id']);
            $table->unsignedBigInteger('product_id')->nullable(false)->change();
            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();

            if (Schema::hasColumn('histories', 'amount')) {
                $table->dropColumn('amount');
            }
            if (Schema::hasColumn('histories', 'quantity')) {
                $table->dropColumn('quantity');
            }
        });
    }
};
