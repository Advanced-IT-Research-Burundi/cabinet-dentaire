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
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->date('birth_date');
            $table->enum('gender', ["M","F","Autre"]);
            $table->string('phone', 20)->nullable();
            $table->string('secondary_phone', 20)->nullable();
            $table->string('email')->unique()->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('postal_code', 20)->nullable();
            $table->string('country')->nullable();
            $table->string('insurance_number')->nullable();
            $table->string('insurance_company')->nullable();
            $table->foreignId('insurance_id')->nullable()->constrained('assurances');
            $table->text('medical_history')->nullable();
            $table->text('allergies')->nullable();
            $table->foreignId('creator_id')->constrained('users');
             // 'physique' ou 'morale'
            $table->string('patient_type')->default('physique');
            $table->string('nif')->nullable();
            $table->string('societe')->nullable();

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
