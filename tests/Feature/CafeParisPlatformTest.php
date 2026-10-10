<?php

use App\Models\Category;
use App\Models\Commande;
use App\Models\Facture;
use App\Models\Plat;
use App\Models\User;
use App\Models\ZoneLivraison;
use Database\Seeders\DatabaseSeeder;

beforeEach(function () {
    $this->seed(DatabaseSeeder::class);
});

test('public pages are accessible and load properly', function () {
    $this->get(route('home'))->assertStatus(200)->assertSee('CAFÉ PARIS');
    $this->get(route('menu.index'))->assertStatus(200)->assertSee('Nos Menus');
    $this->get(route('about'))->assertStatus(200)->assertSee('Notre Histoire');
    $this->get(route('contact'))->assertStatus(200)->assertSee('Contactez-Nous');
    $this->get(route('reservation.create'))->assertStatus(200)->assertSee('Réservation de Table');
    $this->get(route('checkout.index'))->assertStatus(200)->assertSee('Finaliser ma Commande');
});

test('client can place an order successfully and get tracked', function () {
    $plat = Plat::first();
    $zone = ZoneLivraison::first();

    $cartData = json_encode([
        [
            'id' => $plat->id,
            'type' => 'plat',
            'qty' => 2,
            'price' => (float) $plat->prix_fcfa,
            'note' => 'Sans oignons',
        ],
    ]);

    $response = $this->post(route('checkout.process'), [
        'nom_client' => 'Kossi Mensah Test',
        'telephone_client' => '90123456',
        'email_client' => 'kossi@example.tg',
        'type' => 'livraison',
        'zone_livraison_id' => $zone->id,
        'adresse_livraison' => 'Tokoin Ramco, Rue de la Paix',
        'quartier' => 'Tokoin',
        'latitude' => 6.1375,
        'longitude' => 1.2123,
        'note_livreur' => 'Portail bleu',
        'mode_paiement' => 'especes_livraison',
        'panier_data' => $cartData,
    ]);

    $commande = Commande::where('nom_client', 'Kossi Mensah Test')->first();
    expect($commande)->not->toBeNull();
    expect($commande->statut)->toBe('en_attente');
    expect((float) $commande->frais_livraison_fcfa)->toBe((float) $zone->frais_fcfa);

    // Verify invoice was automatically created
    $facture = Facture::where('commande_id', $commande->id)->first();
    expect($facture)->not->toBeNull();
    expect($facture->numero_facture)->toContain('RAF-2026-');

    $response->assertRedirect(route('suivi.commande', $commande->tracking_token));

    // Follow redirect to tracking page
    $trackingResponse = $this->get(route('suivi.commande', $commande->tracking_token));
    $trackingResponse->assertStatus(200)->assertSee($commande->code_commande);

    // Test tracking api status
    $apiResponse = $this->get(route('api.suivi.statut', $commande->tracking_token));
    $apiResponse->assertStatus(200)->assertJson([
        'code_commande' => $commande->code_commande,
        'statut' => 'en_attente',
    ]);
});

test('table reservation can be created', function () {
    $response = $this->post(route('reservation.store'), [
        'nom' => 'Akouvi Test',
        'telephone' => '+228 91 22 33 44',
        'email' => 'akouvi@test.com',
        'nombre_personnes' => 4,
        'date_reservation' => now()->addDay()->format('Y-m-d'),
        'heure_reservation' => '20h00',
        'occasion' => 'anniversaire',
        'remarques' => 'Terrasse vue mer',
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('reservations', [
        'nom' => 'Akouvi Test',
        'statut' => 'en_attente',
    ]);
});

test('admin can access dashboard and manage orders', function () {
    $admin = User::where('role', 'admin')->first();

    // Without login should redirect
    $this->get(route('admin.dashboard'))->assertRedirect(route('login'));

    // As admin
    $this->actingAs($admin)
        ->get(route('admin.dashboard'))
        ->assertStatus(200)
        ->assertSee('Tableau de Bord');

    $this->actingAs($admin)
        ->get(route('admin.commandes.index'))
        ->assertStatus(200)
        ->assertSee('Commandes');

    $this->actingAs($admin)
        ->get(route('admin.menus.index'))
        ->assertStatus(200)
        ->assertSee('Plats de la Carte');

    $this->actingAs($admin)
        ->get(route('admin.factures.index'))
        ->assertStatus(200)
        ->assertSee('Facturation');

    $this->actingAs($admin)
        ->get(route('admin.revenus.index'))
        ->assertStatus(200)
        ->assertSee('Chiffre d’Affaires');
});

test('courier can access deliveries and update status', function () {
    $livreur = User::where('role', 'livreur')->first();
    $commande = Commande::where('statut', 'prete')->first();

    $this->actingAs($livreur)
        ->get(route('coursier.dashboard'))
        ->assertStatus(200)
        ->assertSee('Café Paris Livraisons');

    if ($commande) {
        $this->actingAs($livreur)
            ->post(route('coursier.start', $commande->id))
            ->assertRedirect();

        expect($commande->fresh()->statut)->toBe('en_livraison');

        $this->actingAs($livreur)
            ->post(route('coursier.complete', $commande->id))
            ->assertRedirect(route('coursier.dashboard'));

        expect($commande->fresh()->statut)->toBe('livree');
    }
});

test('kitchen staff can access KDS screen and change dish status', function () {
    $staff = User::where('role', 'staff')->first();
    $commande = Commande::first();

    $this->actingAs($staff)
        ->get(route('cuisine.dashboard'))
        ->assertStatus(200)
        ->assertSee('Écran Cuisine');

    $this->actingAs($staff)
        ->post(route('cuisine.update_status', $commande->id), ['statut' => 'en_preparation'])
        ->assertRedirect();

    expect($commande->fresh()->statut)->toBe('en_preparation');
});
