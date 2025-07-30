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
            $table->string('product_name');
            $table->string('marque')->nullable();
            $table->string('unite_mesure')->nullable();
            $table->double('quantite',62,2)->default(0);
            $table->double('quantite_alert',62,2)->default(0);
            $table->double('price',62,2)->default(0);
            $table->double('price_ttc',62,2)->default(0);
            $table->double('price_max',62,2)->default(0);
            $table->double('price_tvac',62,2)->default(0);
            $table->double('taux_tva',62,2)->default(0);
            $table->double('item_ott_tax',62,2)->default(0);
            $table->double('item_tsce_tax',62,2)->default(0);
            $table->double('price_min',62,2)->default(0);
            $table->date('date_expiration')->nullable();
            $table->text('description')->nullable();
            $table->string('location', 100)->nullable();
            $table->string('code_product', 100)->nullable();
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->onDelete('set null');
            $table->foreignId('user_id');
            $table->foreignId('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->enum('status', ["Disponible","Faible_stock","En_rupture","Expire"])->default("Disponible");
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
