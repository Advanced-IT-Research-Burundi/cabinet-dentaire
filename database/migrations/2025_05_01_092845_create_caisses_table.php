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

        Schema::create('caisses', function (Blueprint $table) {
            $table->id();
            $table->string('type', 250)->nullable();
            $table->timestamp('date');
            $table->double('montant')->default('0');
            $table->string('description', 250)->nullable();
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
        Schema::dropIfExists('caisses');
    }
};
