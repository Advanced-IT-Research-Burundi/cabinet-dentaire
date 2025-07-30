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
        Schema::create('obr_pointers', function (Blueprint $table) {
            $table->id();
            $table->text('invoice_id')->nullable();
            $table->text('invoice_signature')->nullable();
            $table->string('status', 20)->nullable();
            $table->text('electronic_signature')->nullable();
            $table->text('msg')->nullable();
            $table->text('result')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('obr_pointers');
    }
};
