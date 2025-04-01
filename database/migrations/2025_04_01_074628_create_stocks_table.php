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
        Schema::disableForeignKeyConstraints();

        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->string('product_name', 255);
            $table->string('category', 100);
            $table->text('description')->nullable();
            $table->integer('available_quantity');
            $table->string('unit_measure', 50)->nullable();
            $table->integer('minimum_quantity');
            $table->date('last_order_date')->nullable();
            $table->decimal('purchase_price', 10, 2)->nullable();
            $table->string('supplier', 255)->nullable();
            $table->string('location', 100)->nullable();
            $table->date('expiration_date')->nullable();
            $table->enum('status', ["Disponible","Faible_stock","En_rupture","Expire"]);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};
