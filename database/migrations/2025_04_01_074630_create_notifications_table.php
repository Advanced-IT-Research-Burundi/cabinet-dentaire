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

        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->text('message');
            $table->enum('type', ["Rappel_RDV","Facture","Paiement","Stock","Systeme"]);
            $table->foreignId('recipient_id');
            $table->enum('recipient_type', ["Patient","Dentiste","Utilisateur"]);
            $table->string('link', 255)->nullable();
            $table->enum('status', ["Non_envoye","Envoye","Lu"]);
            $table->dateTime('sent_date');
            $table->dateTime('read_date');
            $table->enum('send_method', ["Email","SMS","Application","Tous"]);
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
        Schema::dropIfExists('notifications');
    }
};
