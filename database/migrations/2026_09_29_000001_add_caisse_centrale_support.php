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
        Schema::table('caisses', function (Blueprint $table) {
            $table->boolean('is_centrale')->default(false)->after('name')->index();
        });

        Schema::table('caisse_details', function (Blueprint $table) {
            $table->string('operation_type', 50)->nullable()->after('type');
            $table->string('sens', 10)->nullable()->after('operation_type'); // entree | sortie
            $table->date('date_operation')->nullable()->after('sens');
            $table->string('reference')->nullable()->after('date_operation');
            $table->string('banque')->nullable()->after('reference');
            $table->string('justificatif')->nullable()->after('banque');
            $table->foreignId('source_caisse_id')->nullable()->after('caisse_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('caisse_details', function (Blueprint $table) {
            $table->dropColumn([
                'operation_type',
                'sens',
                'date_operation',
                'reference',
                'banque',
                'justificatif',
                'source_caisse_id',
            ]);
        });

        Schema::table('caisses', function (Blueprint $table) {
            $table->dropIndex(['is_centrale']);
            $table->dropColumn('is_centrale');
        });
    }
};
