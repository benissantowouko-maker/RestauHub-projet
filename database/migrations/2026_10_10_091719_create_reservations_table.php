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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->string('code_reservation')->unique();
            $table->string('nom');
            $table->string('telephone');
            $table->string('email')->nullable();
            $table->integer('nombre_personnes')->default(2);
            $table->date('date_reservation');
            $table->string('heure_reservation');
            $table->string('statut')->default('en_attente'); // en_attente, confirmee, annulee, terminee
            $table->string('occasion')->nullable();
            $table->text('remarques')->nullable();
            $table->string('table_numero')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
