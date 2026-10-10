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
        Schema::create('zones_livraison', function (Blueprint $table) {
            $table->id();
            $table->string('nom_zone');
            $table->decimal('frais_fcfa', 10, 2)->default(1000);
            $table->integer('delai_estime_min')->default(30);
            $table->string('description')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zones_livraison');
    }
};
