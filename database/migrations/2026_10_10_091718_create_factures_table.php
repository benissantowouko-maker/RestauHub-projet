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
        Schema::create('factures', function (Blueprint $table) {
            $table->id();
            $table->string('numero_facture')->unique();
            $table->foreignId('commande_id')->constrained('commandes')->onDelete('cascade');
            $table->decimal('montant_fcfa', 10, 2);
            $table->string('statut_envoi')->default('envoyee'); // en_attente, envoyee, echec
            $table->string('fichier_pdf_url')->nullable();
            $table->timestamp('date_emission')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('factures');
    }
};
