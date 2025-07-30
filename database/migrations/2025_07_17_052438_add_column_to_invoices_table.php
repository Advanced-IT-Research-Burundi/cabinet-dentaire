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
        Schema::table('invoices', function (Blueprint $table) {

            $table->date('invoice_date')->default(now());
            $table->string('invoice_type', 2)->default('FN');
            $table->string('payment_type', 2)->default('1');
            $table->string('invoice_currency', 3)->default('BIF');
            $table->string('cancelled_invoice_ref', 100)->default('');
            $table->string('invoice_ref', 100)->default('');
            $table->string('cn_motif', 100)->default('');
            $table->string('invoice_identifier', 100)->default('');
            $table->json('invoice_items')->default('[]');
            $table->string('is_sent_to_obr', 1)->default('0');
            $table->string('is_sent_at', 1)->default('0');
            $table->string('is_canceled', 1)->default('0');
            $table->string('is_canceled_at', 1)->default('0');
            $table->string('is_canceled_by', 1)->default('0');
            $table->string('is_canceled_reason', 1)->default('0');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn([
                'invoice_number',
                'invoice_date',
                'invoice_type',
                'payment_type',
                'invoice_currency',
                'cancelled_invoice_ref',
                'invoice_ref',
                'cn_motif',
                'invoice_identifier',
                'invoice_items'
            ]);
        });
    }
};
