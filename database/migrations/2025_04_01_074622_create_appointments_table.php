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

        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained();
            $table->foreignId('dentist_id')->constrained();
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->text('reason')->nullable();
            $table->enum('status', ["Confirme","Annule","Termine","En_attente","Reporte"]);
            $table->text('notes')->nullable();
            $table->boolean('reminder_sent')->default(false);
            $table->foreignId('creator_id')->constrained('users');
            $table->foreignId('planned_treatment_id')->nullable()->constrained('treatment_types');
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
        Schema::dropIfExists('appointments');
    }
};
