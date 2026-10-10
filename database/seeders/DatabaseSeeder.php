<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Client;
use App\Models\Commande;
use App\Models\Facture;
use App\Models\Formule;
use App\Models\LigneCommande;
use App\Models\Message;
use App\Models\Parametre;
use App\Models\Plat;
use App\Models\Reservation;
use App\Models\User;
use App\Models\ZoneLivraison;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Paramètres du Restaurant
        $parametres = [
            'nom_restaurant' => 'Café Paris Lomé',
            'slogan' => 'L’élégance de la brasserie parisienne au cœur de Lomé',
            'telephone_principal' => '+228 90 00 12 34',
            'telephone_secondaire' => '+228 98 00 12 34',
            'email_contact' => 'contact@cafeparis-lome.site',
            'adresse' => 'Boulevard de la Marina, Angle Rue de la Paix, Lomé, Togo',
            'horaires' => 'Tous les jours : 08h00 - 23h30 (Service continu)',
            'devise' => 'FCFA',
            'numero_tmoney' => '+228 90 00 12 34',
            'numero_flooz' => '+228 98 00 12 34',
            'frais_livraison_defaut' => '1000',
        ];

        foreach ($parametres as $cle => $valeur) {
            Parametre::set($cle, $valeur);
        }

        // 2. Utilisateurs internes (Admin, Staff Cuisine, Livreurs, Client de test)
        $admin = User::firstOrCreate(
            ['email' => 'admin@cafeparis.com'],
            [
                'name' => 'Directeur Café Paris',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'telephone' => '+228 90 11 22 33',
                'actif' => true,
            ]
        );

        $staff = User::firstOrCreate(
            ['email' => 'cuisine@cafeparis.com'],
            [
                'name' => 'Chef Cuisine Lomé',
                'password' => Hash::make('password'),
                'role' => 'staff',
                'telephone' => '+228 91 22 33 44',
                'actif' => true,
            ]
        );

        $livreur1 = User::firstOrCreate(
            ['email' => 'livreur1@cafeparis.com'],
            [
                'name' => 'Kossi Mensah (Coursier 1)',
                'password' => Hash::make('password'),
                'role' => 'livreur',
                'telephone' => '+228 92 34 56 78',
                'actif' => true,
            ]
        );

        $livreur2 = User::firstOrCreate(
            ['email' => 'livreur2@cafeparis.com'],
            [
                'name' => 'Afi Kodjo (Coursier 2)',
                'password' => Hash::make('password'),
                'role' => 'livreur',
                'telephone' => '+228 93 45 67 89',
                'actif' => true,
            ]
        );

        $clientUser = User::firstOrCreate(
            ['email' => 'client@cafeparis.com'],
            [
                'name' => 'Yao Amegassi',
                'password' => Hash::make('password'),
                'role' => 'client',
                'telephone' => '+228 90 87 65 43',
                'actif' => true,
            ]
        );

        // 3. Zones de livraison à Lomé
        $zones = [
            [
                'nom_zone' => 'Centre-Ville / Marina / Kodjoviakopé',
                'frais_fcfa' => 1000,
                'delai_estime_min' => 20,
                'description' => 'Zone centrale, front de mer et quartiers historiques',
                'active' => true,
            ],
            [
                'nom_zone' => 'Tokoin / Nukafu / Adéwui / Ramco',
                'frais_fcfa' => 1000,
                'delai_estime_min' => 25,
                'description' => 'Quartiers centraux et administratifs de Lomé',
                'active' => true,
            ],
            [
                'nom_zone' => 'Bè / Akodésséwa / Port Autonome',
                'frais_fcfa' => 1500,
                'delai_estime_min' => 30,
                'description' => 'Zone Est et abords du grand marché / port',
                'active' => true,
            ],
            [
                'nom_zone' => 'Hedzranawoé / Forever / Totsi',
                'frais_fcfa' => 1500,
                'delai_estime_min' => 30,
                'description' => 'Secteur nord-est et aéroport Gnassingbé Eyadéma',
                'active' => true,
            ],
            [
                'nom_zone' => 'Agoè-Nyivé / Cacavéli / Légbassito',
                'frais_fcfa' => 2000,
                'delai_estime_min' => 40,
                'description' => 'Grande zone nord résidentielle et carrefour 2 Lions',
                'active' => true,
            ],
            [
                'nom_zone' => 'Baguida / Avépozo / Côte Est',
                'frais_fcfa' => 2500,
                'delai_estime_min' => 45,
                'description' => 'Route d’Aného, complexes hôteliers et résidences côtières',
                'active' => true,
            ],
        ];

        foreach ($zones as $zoneData) {
            ZoneLivraison::updateOrCreate(['nom_zone' => $zoneData['nom_zone']], $zoneData);
        }

        // 4. Catégories
        $categoriesData = [
            [
                'nom' => 'Entrées & Salades',
                'slug' => 'entrees-et-salades',
                'description' => 'Fraîcheur, salades composées et entrées gourmandes',
                'icone' => 'salad',
                'photo_url' => 'https://images.unsplash.com/photo-1540420773420-3366772f4999?w=600&auto=format&fit=crop&q=80',
                'ordre_affichage' => 1,
            ],
            [
                'nom' => 'Nos Pizzas Artisanales',
                'slug' => 'nos-pizzas-artisanales',
                'description' => 'Pâte fine maison cuite au feu de bois avec mozzarella fondante',
                'icone' => 'pizza',
                'photo_url' => 'https://images.unsplash.com/photo-1513104890138-7c749659a591?w=600&auto=format&fit=crop&q=80',
                'ordre_affichage' => 2,
            ],
            [
                'nom' => 'Burgers & Chawarmas',
                'slug' => 'burgers-et-chawarmas',
                'description' => 'Burgers généreux au pain brioché et chawarmas parfumés',
                'icone' => 'burger',
                'photo_url' => 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=600&auto=format&fit=crop&q=80',
                'ordre_affichage' => 3,
            ],
            [
                'nom' => 'Volailles & Grillades',
                'slug' => 'volailles-et-grillades',
                'description' => 'Poulet fermier braisé, magret de canard et viandes tendres',
                'icone' => 'meat',
                'photo_url' => 'https://images.unsplash.com/photo-1598515214211-89d3c73ae83b?w=600&auto=format&fit=crop&q=80',
                'ordre_affichage' => 4,
            ],
            [
                'nom' => 'Poissons & Crustacés',
                'slug' => 'poissons-et-crustaces',
                'description' => 'Pêche fraîche de Lomé : dorade, capitaine et gambas marinées',
                'icone' => 'fish',
                'photo_url' => 'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?w=600&auto=format&fit=crop&q=80',
                'ordre_affichage' => 5,
            ],
            [
                'nom' => 'Pâtes & Spaghettis',
                'slug' => 'pates-et-spaghettis',
                'description' => 'Spaghettis aux fruits de mer, bolognaise mijotée et carbonara',
                'icone' => 'utensils',
                'photo_url' => 'https://images.unsplash.com/photo-1621996346565-e3d5d6281729?w=600&auto=format&fit=crop&q=80',
                'ordre_affichage' => 6,
            ],
            [
                'nom' => 'Brochettes & Couscous',
                'slug' => 'brochettes-et-couscous',
                'description' => 'Brochettes de zébu tendre, couscous royal parfumé',
                'icone' => 'flame',
                'photo_url' => 'https://images.unsplash.com/photo-1555939594-58d7cb561ad1?w=600&auto=format&fit=crop&q=80',
                'ordre_affichage' => 7,
            ],
            [
                'nom' => 'Menu Enfant',
                'slug' => 'menu-enfant',
                'description' => 'Portions adaptées, nuggets croustillants et frites dorées',
                'icone' => 'smile',
                'photo_url' => 'https://images.unsplash.com/photo-1562967914-608f82629710?w=600&auto=format&fit=crop&q=80',
                'ordre_affichage' => 8,
            ],
            [
                'nom' => 'Desserts & Glaces',
                'slug' => 'desserts-et-glaces',
                'description' => 'Crème brûlée vanille bourbon, tarte tatin et glaces artisanales',
                'icone' => 'cake',
                'photo_url' => 'https://images.unsplash.com/photo-1587314168485-3236d6710814?w=600&auto=format&fit=crop&q=80',
                'ordre_affichage' => 9,
            ],
            [
                'nom' => 'Boissons & Cocktails',
                'slug' => 'boissons-et-cocktails',
                'description' => 'Bissap frais maison, jus de mangue pressé, vins et cocktails signatures',
                'icone' => 'coffee',
                'photo_url' => 'https://images.unsplash.com/photo-1513558161293-cdaf765ed2fd?w=600&auto=format&fit=crop&q=80',
                'ordre_affichage' => 10,
            ],
        ];

        $categories = [];
        foreach ($categoriesData as $c) {
            $categories[$c['slug']] = Category::updateOrCreate(['slug' => $c['slug']], $c);
        }

        // 5. Plats
        $plats = [
            // Entrées & Salades
            [
                'categorie_id' => $categories['entrees-et-salades']->id,
                'nom' => 'Salade Parisienne au Chèvre Chaud',
                'slug' => 'salade-parisienne-au-chevre-chaud',
                'description' => 'Mesclun de jeunes pousses, toasts de chèvre doré au miel, noix torréfiées et vinaigrette balsamique.',
                'prix_fcfa' => 4500,
                'photo_url' => 'https://images.unsplash.com/photo-1540420773420-3366772f4999?w=600&auto=format&fit=crop&q=80',
                'disponible' => true,
                'est_populaire' => true,
                'temps_preparation_minutes' => 15,
                'ordre_affichage' => 1,
            ],
            [
                'categorie_id' => $categories['entrees-et-salades']->id,
                'nom' => 'Salade Niçoise du Chef',
                'slug' => 'salade-nicoise-du-chef',
                'description' => 'Thon frais mariné, œufs mollets, haricots verts croquants, olives noires et anchois de Méditerranée.',
                'prix_fcfa' => 5000,
                'photo_url' => 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=600&auto=format&fit=crop&q=80',
                'disponible' => true,
                'est_populaire' => false,
                'temps_preparation_minutes' => 15,
                'ordre_affichage' => 2,
            ],
            [
                'categorie_id' => $categories['entrees-et-salades']->id,
                'nom' => 'Carpaccio de Bœuf au Parmesan',
                'slug' => 'carpaccio-de-boeuf-au-parmesan',
                'description' => 'Fines tranches de filet de bœuf, copeaux de parmesan affiné, câpres et filet d’huile d’olive extra vierge.',
                'prix_fcfa' => 5500,
                'photo_url' => 'https://images.unsplash.com/photo-1544025162-d76694265947?w=600&auto=format&fit=crop&q=80',
                'disponible' => true,
                'est_populaire' => false,
                'temps_preparation_minutes' => 12,
                'ordre_affichage' => 3,
            ],

            // Pizzas
            [
                'categorie_id' => $categories['nos-pizzas-artisanales']->id,
                'nom' => 'Pizza Royale Café Paris',
                'slug' => 'pizza-royale-cafe-paris',
                'description' => 'Sauce tomate mijotée, mozzarella fior di latte, jambon supérieur, champignons de Paris frais et origan sauvage.',
                'prix_fcfa' => 6500,
                'photo_url' => 'https://images.unsplash.com/photo-1513104890138-7c749659a591?w=600&auto=format&fit=crop&q=80',
                'disponible' => true,
                'est_populaire' => true,
                'temps_preparation_minutes' => 20,
                'ordre_affichage' => 1,
            ],
            [
                'categorie_id' => $categories['nos-pizzas-artisanales']->id,
                'nom' => 'Pizza 4 Fromages Affinés',
                'slug' => 'pizza-4-fromages-affines',
                'description' => 'Mozzarella, gorgonzola fondant, chèvre frais du terroir et copeaux de parmesan reggiano.',
                'prix_fcfa' => 7000,
                'photo_url' => 'https://images.unsplash.com/photo-1573821663912-569905455b1c?w=600&auto=format&fit=crop&q=80',
                'disponible' => true,
                'est_populaire' => true,
                'temps_preparation_minutes' => 20,
                'ordre_affichage' => 2,
            ],
            [
                'categorie_id' => $categories['nos-pizzas-artisanales']->id,
                'nom' => 'Pizza Fruits de Mer de Lomé',
                'slug' => 'pizza-fruits-de-mer-de-lome',
                'description' => 'Crevettes fraîches, calamars tendres, persillade à l’ail et mozzarella crémeuse.',
                'prix_fcfa' => 8000,
                'photo_url' => 'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=600&auto=format&fit=crop&q=80',
                'disponible' => true,
                'est_populaire' => false,
                'temps_preparation_minutes' => 25,
                'ordre_affichage' => 3,
            ],

            // Burgers & Chawarmas
            [
                'categorie_id' => $categories['burgers-et-chawarmas']->id,
                'nom' => 'Burger Café Paris Gourmet',
                'slug' => 'burger-cafe-paris-gourmet',
                'description' => 'Steak haché de bœuf Black Angus 180g, cheddar rouge affiné, bacon croustillant, oignons confits, frites maison.',
                'prix_fcfa' => 6000,
                'photo_url' => 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=600&auto=format&fit=crop&q=80',
                'disponible' => true,
                'est_populaire' => true,
                'temps_preparation_minutes' => 20,
                'ordre_affichage' => 1,
            ],
            [
                'categorie_id' => $categories['burgers-et-chawarmas']->id,
                'nom' => 'Chawarma Libanais Poulet Épicé',
                'slug' => 'chawarma-libanais-poulet-epice',
                'description' => 'Galette libanaise croustillante, émincé de poulet mariné au sumac, crème d’ail toum, cornichons et frites.',
                'prix_fcfa' => 3500,
                'photo_url' => 'https://images.unsplash.com/photo-1529006557810-274b9b2fc783?w=600&auto=format&fit=crop&q=80',
                'disponible' => true,
                'est_populaire' => true,
                'temps_preparation_minutes' => 15,
                'ordre_affichage' => 2,
            ],
            [
                'categorie_id' => $categories['burgers-et-chawarmas']->id,
                'nom' => 'Chawarma Spécial Bœuf Émincé',
                'slug' => 'chawarma-special-boeuf-emince',
                'description' => 'Bœuf tendre mariné aux 7 épices, sauce tahina maison, tomates fraîches, persil et oignons rouges.',
                'prix_fcfa' => 4000,
                'photo_url' => 'https://images.unsplash.com/photo-1561651823-34feb02250e4?w=600&auto=format&fit=crop&q=80',
                'disponible' => true,
                'est_populaire' => false,
                'temps_preparation_minutes' => 15,
                'ordre_affichage' => 3,
            ],

            // Volailles & Grillades
            [
                'categorie_id' => $categories['volailles-et-grillades']->id,
                'nom' => 'Poulet Braisé Façon Café Paris',
                'slug' => 'poulet-braise-facon-cafe-paris',
                'description' => 'Demi-poulet fermier mariné au gingembre, ail et piment doux, braisé à point. Servi avec alloco et attiéké.',
                'prix_fcfa' => 6500,
                'photo_url' => 'https://images.unsplash.com/photo-1598515214211-89d3c73ae83b?w=600&auto=format&fit=crop&q=80',
                'disponible' => true,
                'est_populaire' => true,
                'temps_preparation_minutes' => 30,
                'ordre_affichage' => 1,
            ],
            [
                'categorie_id' => $categories['volailles-et-grillades']->id,
                'nom' => 'Entrecôte Grillée Maître d’Hôtel',
                'slug' => 'entrecote-grillee-maitre-d-hotel',
                'description' => 'Pièce de bœuf tendre 250g, beurre persillé maison, frites fraîches croustillantes et fagot de haricots verts.',
                'prix_fcfa' => 9500,
                'photo_url' => 'https://images.unsplash.com/photo-1558030006-450675393462?w=600&auto=format&fit=crop&q=80',
                'disponible' => true,
                'est_populaire' => true,
                'temps_preparation_minutes' => 25,
                'ordre_affichage' => 2,
            ],

            // Poissons & Crustacés
            [
                'categorie_id' => $categories['poissons-et-crustaces']->id,
                'nom' => 'Capitaine Braisé au Feu de Bois',
                'slug' => 'capitaine-braise-au-feu-de-bois',
                'description' => 'Filet de capitaine de pêche locale, marinade aux aromates du Golfe de Guinée, servi avec banane plantain frite.',
                'prix_fcfa' => 8500,
                'photo_url' => 'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?w=600&auto=format&fit=crop&q=80',
                'disponible' => true,
                'est_populaire' => true,
                'temps_preparation_minutes' => 30,
                'ordre_affichage' => 1,
            ],
            [
                'categorie_id' => $categories['poissons-et-crustaces']->id,
                'nom' => 'Gambas Géantes Sautées à l’Ail & Persil',
                'slug' => 'gambas-geantes-sautees-a-l-ail',
                'description' => 'Gambas entières déglacées au vin blanc et beurre citronné, servies avec riz basmati parfumé.',
                'prix_fcfa' => 11000,
                'photo_url' => 'https://images.unsplash.com/photo-1565680018434-b513d5e5fd47?w=600&auto=format&fit=crop&q=80',
                'disponible' => true,
                'est_populaire' => false,
                'temps_preparation_minutes' => 25,
                'ordre_affichage' => 2,
            ],

            // Pâtes & Spaghettis
            [
                'categorie_id' => $categories['pates-et-spaghettis']->id,
                'nom' => 'Spaghetti Frutti di Mare (Fruits de Mer)',
                'slug' => 'spaghetti-frutti-di-mare',
                'description' => 'Spaghettis al dente aux gambas, moules, calamars, tomates cerises confites et persil frais.',
                'prix_fcfa' => 7500,
                'photo_url' => 'https://images.unsplash.com/photo-1621996346565-e3d5d6281729?w=600&auto=format&fit=crop&q=80',
                'disponible' => true,
                'est_populaire' => true,
                'temps_preparation_minutes' => 20,
                'ordre_affichage' => 1,
            ],
            [
                'categorie_id' => $categories['pates-et-spaghettis']->id,
                'nom' => 'Tagliatelles Carbonara Traditionnelle',
                'slug' => 'tagliatelles-carbonara-traditionnelle',
                'description' => 'Pâtes fraîches, guanciale croustillant, jaunes d’œufs battus au pecorino romano et poivre noir concassé.',
                'prix_fcfa' => 6000,
                'photo_url' => 'https://images.unsplash.com/photo-1612874742237-6526221588e3?w=600&auto=format&fit=crop&q=80',
                'disponible' => true,
                'est_populaire' => false,
                'temps_preparation_minutes' => 20,
                'ordre_affichage' => 2,
            ],

            // Brochettes & Couscous
            [
                'categorie_id' => $categories['brochettes-et-couscous']->id,
                'nom' => 'Couscous Royal d’Orient & Maghreb',
                'slug' => 'couscous-royal-d-orient-et-maghreb',
                'description' => 'Semoule fine cuite à la vapeur, agneau mijoté, poulet rôti, merguez piquantes et bouillon de légumes frais.',
                'prix_fcfa' => 9000,
                'photo_url' => 'https://images.unsplash.com/photo-1541518763669-27fef04b14ea?w=600&auto=format&fit=crop&q=80',
                'disponible' => true,
                'est_populaire' => true,
                'temps_preparation_minutes' => 25,
                'ordre_affichage' => 1,
            ],
            [
                'categorie_id' => $categories['brochettes-et-couscous']->id,
                'nom' => 'Trio de Brochettes Mixtes (Bœuf & Volaille)',
                'slug' => 'trio-de-brochettes-mixtes',
                'description' => 'Trois brochettes marinées aux épices togolaises (Kankan), poivrons croquants et alloco bien doré.',
                'prix_fcfa' => 6000,
                'photo_url' => 'https://images.unsplash.com/photo-1555939594-58d7cb561ad1?w=600&auto=format&fit=crop&q=80',
                'disponible' => true,
                'est_populaire' => true,
                'temps_preparation_minutes' => 20,
                'ordre_affichage' => 2,
            ],

            // Menu Enfant
            [
                'categorie_id' => $categories['menu-enfant']->id,
                'nom' => 'Menu P’tit Parisien',
                'slug' => 'menu-ptit-parisien',
                'description' => 'Nuggets de poulet maison ou Steak haché, frites dorées, boisson au choix et pot de glace vanille.',
                'prix_fcfa' => 3500,
                'photo_url' => 'https://images.unsplash.com/photo-1562967914-608f82629710?w=600&auto=format&fit=crop&q=80',
                'disponible' => true,
                'est_populaire' => false,
                'temps_preparation_minutes' => 15,
                'ordre_affichage' => 1,
            ],

            // Desserts
            [
                'categorie_id' => $categories['desserts-et-glaces']->id,
                'nom' => 'Crème Brûlée à la Vanille de Madagascar',
                'slug' => 'creme-brulee-vanille',
                'description' => 'Crème onctueuse cuite lentement et caramélisée à la cassonade minute sous vos yeux.',
                'prix_fcfa' => 3000,
                'photo_url' => 'https://images.unsplash.com/photo-1587314168485-3236d6710814?w=600&auto=format&fit=crop&q=80',
                'disponible' => true,
                'est_populaire' => true,
                'temps_preparation_minutes' => 10,
                'ordre_affichage' => 1,
            ],
            [
                'categorie_id' => $categories['desserts-et-glaces']->id,
                'nom' => 'Moelleux au Chocolat & Glace Vanille',
                'slug' => 'moelleux-au-chocolat-glace-vanille',
                'description' => 'Cœur coulant au chocolat noir pur cacao 70%, boule de glace artisanale et éclats de noisettes.',
                'prix_fcfa' => 3500,
                'photo_url' => 'https://images.unsplash.com/photo-1606313564200-e75d5e30476c?w=600&auto=format&fit=crop&q=80',
                'disponible' => true,
                'est_populaire' => true,
                'temps_preparation_minutes' => 12,
                'ordre_affichage' => 2,
            ],

            // Boissons
            [
                'categorie_id' => $categories['boissons-et-cocktails']->id,
                'nom' => 'Cocktail Signature Café Paris (Sans Alcool)',
                'slug' => 'cocktail-signature-cafe-paris',
                'description' => 'Cocktail glacé : jus d’ananas pressé de Badja, passion, menthe fraîche et touche de gingembre doux.',
                'prix_fcfa' => 2500,
                'photo_url' => 'https://images.unsplash.com/photo-1513558161293-cdaf765ed2fd?w=600&auto=format&fit=crop&q=80',
                'disponible' => true,
                'est_populaire' => true,
                'temps_preparation_minutes' => 5,
                'ordre_affichage' => 1,
            ],
            [
                'categorie_id' => $categories['boissons-et-cocktails']->id,
                'nom' => 'Jus de Bissap Maison Réserve',
                'slug' => 'jus-de-bissap-maison',
                'description' => 'Infusion de fleurs d’hibiscus togolaises, menthe fraîche, cannelle et juste ce qu’il faut de sucre de canne.',
                'prix_fcfa' => 1500,
                'photo_url' => 'https://images.unsplash.com/photo-1536935338788-846bb9981813?w=600&auto=format&fit=crop&q=80',
                'disponible' => true,
                'est_populaire' => false,
                'temps_preparation_minutes' => 5,
                'ordre_affichage' => 2,
            ],
        ];

        $createdPlats = [];
        foreach ($plats as $p) {
            $createdPlats[$p['slug']] = Plat::updateOrCreate(['slug' => $p['slug']], $p);
        }

        // 6. Formules
        $formulesData = [
            [
                'nom' => 'Formule Chawarma Express',
                'slug' => 'formule-chawarma-express',
                'description' => 'Le déjeuner rapide et savoureux par excellence',
                'composants' => '1 Chawarma au choix (Poulet ou Bœuf) + Portion de frites croustillantes + 1 Canette 33cl ou Jus de Bissap',
                'prix_fcfa' => 4500,
                'photo_url' => 'https://images.unsplash.com/photo-1529006557810-274b9b2fc783?w=600&auto=format&fit=crop&q=80',
                'disponible' => true,
            ],
            [
                'nom' => 'Formule Burger Gourmand',
                'slug' => 'formule-burger-gourmand',
                'description' => 'Pour les amateurs de burgers généreux',
                'composants' => '1 Burger Café Paris Gourmet + Frites maison dorées + 1 Boisson fraîche + 1 Moelleux au chocolat',
                'prix_fcfa' => 8500,
                'photo_url' => 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=600&auto=format&fit=crop&q=80',
                'disponible' => true,
            ],
            [
                'nom' => 'Formule Bistro Parisienne',
                'slug' => 'formule-bistro-parisienne',
                'description' => 'Un repas complet inspiré des boulevards parisiens',
                'composants' => '1 Salade Parisienne au Chèvre Chaud + 1 Entrecôte Maître d’Hôtel ou Poulet Braisé + 1 Crème Brûlée',
                'prix_fcfa' => 13500,
                'photo_url' => 'https://images.unsplash.com/photo-1558030006-450675393462?w=600&auto=format&fit=crop&q=80',
                'disponible' => true,
            ],
        ];

        foreach ($formulesData as $f) {
            Formule::updateOrCreate(['slug' => $f['slug']], $f);
        }

        // 7. Client exemple
        $client = Client::firstOrCreate(
            ['telephone' => '+228 90 87 65 43'],
            [
                'user_id' => $clientUser->id,
                'nom' => 'Yao Amegassi',
                'email' => 'client@cafeparis.com',
                'adresse_par_defaut' => 'Tokoin Ramco, face pharmacie de l’Union, Lomé',
                'compte_utilisateur' => true,
            ]
        );

        $zoneTokoin = ZoneLivraison::where('nom_zone', 'like', '%Tokoin%')->first();
        $zoneCentre = ZoneLivraison::where('nom_zone', 'like', '%Centre-Ville%')->first();

        // 8. Commandes réelles exemples (avec différents statuts)
        // Commande 1: En attente (Nouvelle commande, déclenche notification sonore en admin)
        $cmd1 = Commande::firstOrCreate(
            ['code_commande' => 'CP-2026-0001'],
            [
                'tracking_token' => Str::random(32),
                'client_id' => $client->id,
                'user_id' => $clientUser->id,
                'nom_client' => 'Yao Amegassi',
                'telephone_client' => '+228 90 87 65 43',
                'email_client' => 'client@cafeparis.com',
                'type' => 'livraison',
                'statut' => 'en_attente',
                'zone_livraison_id' => $zoneTokoin?->id,
                'adresse_livraison' => 'Tokoin Ramco, Immeuble Horizon, 2ème étage',
                'quartier' => 'Tokoin',
                'latitude' => 6.1362,
                'longitude' => 1.2185,
                'note_livreur' => 'Portail bleu métallique, sonner au 2B ou appeler à l’arrivée',
                'mode_paiement' => 'especes_livraison',
                'statut_paiement' => 'a_la_livraison',
                'sous_total_fcfa' => 11000,
                'frais_livraison_fcfa' => 1000,
                'montant_total' => 12000,
                'created_at' => Carbon::now()->subMinutes(8),
            ]
        );

        LigneCommande::firstOrCreate(
            ['commande_id' => $cmd1->id, 'nom_produit' => 'Pizza Royale Café Paris'],
            [
                'plat_id' => $createdPlats['pizza-royale-cafe-paris']->id,
                'quantite' => 1,
                'prix_unitaire_fcfa' => 6500,
                'sous_total_fcfa' => 6500,
            ]
        );

        LigneCommande::firstOrCreate(
            ['commande_id' => $cmd1->id, 'nom_produit' => 'Salade Parisienne au Chèvre Chaud'],
            [
                'plat_id' => $createdPlats['salade-parisienne-au-chevre-chaud']->id,
                'quantite' => 1,
                'prix_unitaire_fcfa' => 4500,
                'sous_total_fcfa' => 4500,
            ]
        );

        Facture::firstOrCreate(
            ['numero_facture' => 'RAF-2026-0001'],
            [
                'commande_id' => $cmd1->id,
                'montant_fcfa' => 12000,
                'statut_envoi' => 'envoyee',
                'date_emission' => Carbon::now()->subMinutes(8),
            ]
        );

        // Commande 2: Prête (disponible pour le coursier)
        $cmd2 = Commande::firstOrCreate(
            ['code_commande' => 'CP-2026-0002'],
            [
                'tracking_token' => Str::random(32),
                'nom_client' => 'Akouvi Lawson',
                'telephone_client' => '+228 91 45 67 89',
                'email_client' => 'akouvi.lawson@gmail.com',
                'type' => 'livraison',
                'statut' => 'prete',
                'zone_livraison_id' => $zoneCentre?->id,
                'adresse_livraison' => 'Kodjoviakopé, Rue des Palmiers, près du Consulat',
                'quartier' => 'Kodjoviakopé',
                'latitude' => 6.1245,
                'longitude' => 1.2098,
                'note_livreur' => 'Maison blanche avec grand bougainvillier rose, appeler en arrivant',
                'mode_paiement' => 't_money',
                'statut_paiement' => 'paye',
                'reference_paiement' => 'TMONEY-TOGO-884920',
                'sous_total_fcfa' => 14500,
                'frais_livraison_fcfa' => 1000,
                'montant_total' => 15500,
                'livreur_id' => $livreur1->id,
                'date_assignation_livreur' => Carbon::now()->subMinutes(5),
                'created_at' => Carbon::now()->subMinutes(35),
            ]
        );

        LigneCommande::firstOrCreate(
            ['commande_id' => $cmd2->id, 'nom_produit' => 'Burger Café Paris Gourmet'],
            [
                'plat_id' => $createdPlats['burger-cafe-paris-gourmet']->id,
                'quantite' => 2,
                'prix_unitaire_fcfa' => 6000,
                'sous_total_fcfa' => 12000,
            ]
        );

        LigneCommande::firstOrCreate(
            ['commande_id' => $cmd2->id, 'nom_produit' => 'Cocktail Signature Café Paris (Sans Alcool)'],
            [
                'plat_id' => $createdPlats['cocktail-signature-cafe-paris']->id,
                'quantite' => 1,
                'prix_unitaire_fcfa' => 2500,
                'sous_total_fcfa' => 2500,
            ]
        );

        Facture::firstOrCreate(
            ['numero_facture' => 'RAF-2026-0002'],
            [
                'commande_id' => $cmd2->id,
                'montant_fcfa' => 15500,
                'statut_envoi' => 'envoyee',
                'date_emission' => Carbon::now()->subMinutes(35),
            ]
        );

        // Commande 3: Livrée (pour l'historique et les statistiques)
        $cmd3 = Commande::firstOrCreate(
            ['code_commande' => 'CP-2026-0003'],
            [
                'tracking_token' => Str::random(32),
                'nom_client' => 'Dr. Koffi Badjare',
                'telephone_client' => '+228 92 01 23 45',
                'email_client' => 'badjare.koffi@univ-lome.tg',
                'type' => 'livraison',
                'statut' => 'livree',
                'zone_livraison_id' => $zoneTokoin?->id,
                'adresse_livraison' => 'Campus Universitaire Lomé, Bâtiment Décanal',
                'quartier' => 'Tokoin',
                'latitude' => 6.1750,
                'longitude' => 1.2150,
                'note_livreur' => 'Remis en main propre à l’accueil principal',
                'mode_paiement' => 'flooz',
                'statut_paiement' => 'paye',
                'reference_paiement' => 'FLZ-MOOV-992182',
                'sous_total_fcfa' => 18000,
                'frais_livraison_fcfa' => 1000,
                'montant_total' => 19000,
                'livreur_id' => $livreur1->id,
                'date_assignation_livreur' => Carbon::now()->subHours(2),
                'date_recuperation_livreur' => Carbon::now()->subHours(1)->subMinutes(40),
                'date_livraison' => Carbon::now()->subHours(1)->subMinutes(10),
                'created_at' => Carbon::now()->subHours(2)->subMinutes(15),
            ]
        );

        LigneCommande::firstOrCreate(
            ['commande_id' => $cmd3->id, 'nom_produit' => 'Couscous Royal d’Orient & Maghreb'],
            [
                'plat_id' => $createdPlats['couscous-royal-d-orient-et-maghreb']->id,
                'quantite' => 2,
                'prix_unitaire_fcfa' => 9000,
                'sous_total_fcfa' => 18000,
            ]
        );

        Facture::firstOrCreate(
            ['numero_facture' => 'RAF-2026-0003'],
            [
                'commande_id' => $cmd3->id,
                'montant_fcfa' => 19000,
                'statut_envoi' => 'envoyee',
                'date_emission' => Carbon::now()->subHours(2)->subMinutes(15),
            ]
        );

        // 9. Réservations de table exemples
        Reservation::firstOrCreate(
            ['code_reservation' => 'RES-2026-0012'],
            [
                'nom' => 'Madame Foli Edwige',
                'telephone' => '+228 90 44 55 66',
                'email' => 'edwige.foli@gmail.com',
                'nombre_personnes' => 4,
                'date_reservation' => Carbon::today(),
                'heure_reservation' => '20h00',
                'statut' => 'confirmee',
                'occasion' => 'anniversaire',
                'remarques' => 'Table en terrasse vue mer si possible, merci !',
                'table_numero' => 'T-14',
            ]
        );

        Reservation::firstOrCreate(
            ['code_reservation' => 'RES-2026-0013'],
            [
                'nom' => 'Monsieur Agbeko Claude',
                'telephone' => '+228 91 77 88 99',
                'email' => 'claude.agbeko@ecobank.com',
                'nombre_personnes' => 6,
                'date_reservation' => Carbon::tomorrow(),
                'heure_reservation' => '12h30',
                'statut' => 'en_attente',
                'occasion' => 'affaires',
                'remarques' => 'Déjeuner professionnel, besoin d’un coin calme.',
            ]
        );

        // 10. Messages de contact exemples
        Message::firstOrCreate(
            ['email' => 'sophie.martin@voyage-togo.com'],
            [
                'nom' => 'Sophie Martin',
                'telephone' => '+33 6 12 34 56 78',
                'sujet' => 'Organisation dîner de groupe pour 25 personnes',
                'message' => 'Bonjour l’équipe Café Paris Lomé, nous organisons un séjour à Lomé le mois prochain et aimerions privatiser un espace pour un dîner dégustation de 25 personnes. Proposez-vous un menu sur mesure ? Merci d’avance pour votre retour.',
                'lu' => false,
            ]
        );
    }
}
