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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients');
            $table->double('tax',60,2)->default(0);
            $table->double('total_quantity',60,2)->default(0);
            $table->double('total_sacs',60,2)->default(0);
            $table->double('amount_tax',60,2)->default(0);
            $table->string('type_paiement')->nullable();
            $table->string('type_facture')->nullable();
            $table->string('invoice_currency')->nullable();
            $table->string('invoice_type')->nullable();
            $table->string('invoice_number')->nullable();
            // Added fields
            $table->date('date_emission')->nullable();
            $table->date('date_echeance')->nullable();
            $table->double('amount',60,2)->default(0);
            $table->double('montant_assurance',60,2)->default(0);
            $table->double('montant_patient',60,2)->default(0);
            // Moved fields from invoice_details
            $table->decimal('tax_rate', 5, 2)->default(0);
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('discount_percentage', 5, 2)->default(0);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('insurance_covered_amount', 10, 2)->default(0);
            $table->decimal('patient_amount', 10, 2)->nullable();
            $table->text('products');
            $table->text('company')->nullable();
            $table->text('client')->nullable();
            $table->text('canceled_or_connection')->nullable();
            $table->text('addresse_client')->nullable();
            $table->foreignId('user_id');
            $table->unsignedBigInteger('client_id')->nullable();
            $table->boolean('is_cancelled')->nullable();
            $table->enum('status', ["Brouillon","Emise","Partiellement_payee","Payee","Annulee", "En_Retard"])->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
