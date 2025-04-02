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
        Schema::create('movement_stocks', function (Blueprint $table) {
            $table->id();
            $table->string('system_or_device_id');
            $table->string('item_code');
            $table->string('item_designation');
            $table->double('item_quantity');
            $table->string('item_measurement_unit');
            $table->double('item_purchase_or_sale_price');
            $table->string('item_purchase_or_sale_currency');
            $table->enum('item_movement_type', ['EN','ER','EI','EAJ','ET','EAU','SN','SP','SV','SD','SC','SAJ','ST','SAU']);
            $table->string('item_movement_invoice_ref')->nullable();
            $table->string('item_movement_description')->nullable();
            $table->string('item_movement_date')->nullable();
            $table->string('item_product_detail_id')->nullable();
            $table->string('is_send_to_obr')->nullable();
            $table->dateTime('is_sent_at')->nullable();
            $table->foreignId('user_id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movement_stocks');
    }
};
