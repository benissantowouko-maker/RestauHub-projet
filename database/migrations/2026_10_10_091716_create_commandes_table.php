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
        Schema::create('commandes', function (Blueprint $table) {
            $table->id();
            $table->string('code_commande')->unique();
            $table->string('tracking_token')->unique()->index();
            $table->foreignId('client_id')->nullable()->constrained('clients')->onDelete('set null');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('nom_client');
            $table->string('telephone_client');
            $table->string('email_client')->nullable();
            $table->string('type')->default('livraison'); // livraison, a_emporter, sur_place
            $table->string('statut')->default('en_attente'); // en_attente, confirmee, en_preparation, prete, en_livraison, livree, annulee
            $table->foreignId('zone_livraison_id')->nullable()->constrained('zones_livraison')->onDelete('set null');
            $table->string('adresse_livraison')->nullable();
            $table->string('quartier')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->text('note_livreur')->nullable();
            $table->string('mode_paiement')->default('especes_livraison'); // especes_livraison, t_money, flooz, carte
            $table->string('statut_paiement')->default('a_la_livraison'); // en_attente, paye, a_la_livraison, echec
            $table->string('reference_paiement')->nullable();
            $table->decimal('sous_total_fcfa', 10, 2)->default(0);
            $table->decimal('frais_livraison_fcfa', 10, 2)->default(0);
            $table->decimal('montant_total', 10, 2)->default(0);
            $table->foreignId('livreur_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('date_assignation_livreur')->nullable();
            $table->timestamp('date_recuperation_livreur')->nullable();
            $table->timestamp('date_livraison')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commandes');
    }
};
