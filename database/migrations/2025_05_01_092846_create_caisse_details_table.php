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

        Schema::create('caisse_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('caisse_id')->constrained('caisses,ids');
            $table->string('type', 250)->nullable();
            $table->double('price')->default('0');
            $table->double('total')->default('0');
            $table->string('status', 250)->nullable();
            $table->foreignId('user_id')->constrained('users,ids');
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('caisse_details');
    }
};
