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

        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 100);
            $table->string('middle_name', 100)->nullable();
            $table->string('last_name', 100)->nullable();
            $table->date('birth_date');
            $table->enum('gender', ["M","F","Autre"]);
            $table->string('phone', 20)->nullable();
            $table->string('secondary_phone', 20)->nullable();
            $table->string('email', 100)->unique()->nullable();
            $table->text('address')->nullable();
            $table->string('city', 100)->nullable();
            $table->string('postal_code', 20)->nullable();
            $table->string('country', 100)->nullable();
            $table->string('insurance_number', 100)->nullable();
            $table->string('insurance_company', 100)->nullable();
            $table->foreignId('insurance_id', 100)->constrained('assurances')->nullable();
            $table->text('medical_history')->nullable();
            $table->text('allergies')->nullable();
            $table->foreignId('creator_id')->constrained('users');
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
        Schema::dropIfExists('patients');
    }
};
