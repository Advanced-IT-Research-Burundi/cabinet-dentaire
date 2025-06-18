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

        Schema::create('invoice_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained();
            $table->foreignId('treatment_id')->constrained();
            $table->string('description', 255)->nullable();
            $table->integer('quantity')->default(1);
            $table->double('unit_price')->nullable();
            $table->double('total_amount')->nullable();
            $table->double('tax_rate')->default(0);
            $table->double('tax_amount')->default(0);
            $table->double('discount_percentage')->default(0);
            $table->double('discount_amount')->default(0);
            $table->double('insurance_covered_amount')->default(0);
            $table->double('patient_amount')->nullable();
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
        Schema::dropIfExists('invoice_details');
    }
};
