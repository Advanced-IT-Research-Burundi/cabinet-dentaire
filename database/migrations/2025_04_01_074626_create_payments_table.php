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

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained();
            $table->foreignId('patient_id')->constrained();
            $table->foreignId('payment_method_id')->constrained();
            $table->string('transaction_number', 100)->nullable();
            $table->decimal('amount', 10, 2)->nullable();
            $table->dateTime('payment_date');
            $table->enum('status', ["Valide","En_attente","Rejete","Rembourse"]);
            $table->text('notes')->nullable();
            $table->string('proof_of_payment', 255)->nullable();
            $table->foreignId('operator_id')->constrained('users');
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
        Schema::dropIfExists('payments');
    }
};
