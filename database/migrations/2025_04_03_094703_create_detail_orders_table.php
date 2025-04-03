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
        Schema::create('detail_orders', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id');
            $table->double('quantite' ,62,2);
            $table->double('quantite_stock', 62,2);
            $table->double('price_unitaire',62,2);
            $table->double('embalage',62,2)->nullable();
            $table->string('code_product')->nullable();
            $table->string('name')->nullable();
            $table->string('unite_mesure')->nullable();
            $table->date('date_expiration');
            $table->integer('order_id');
            $table->integer('user_id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_orders');
    }
};
