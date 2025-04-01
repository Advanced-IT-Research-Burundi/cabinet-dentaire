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

        Schema::create('treatments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained();
            $table->foreignId('dentist_id')->constrained();
            $table->foreignId('treatment_type_id')->constrained();
            $table->foreignId('appointment_id')->constrained();
            $table->date('date');
            $table->text('description')->nullable();
            $table->text('medical_notes')->nullable();
            $table->decimal('applied_price', 10, 2)->nullable();
            $table->enum('status', ["Planifie","En_cours","Termine","Annule"]);
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
        Schema::dropIfExists('treatments');
    }
};
