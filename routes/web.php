<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\InvoiceController as AdminInvoiceController;
use App\Http\Controllers\Admin\MenuController as AdminMenuController;
use App\Http\Controllers\Admin\MessageController as AdminMessageController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ReservationController as AdminReservationController;
use App\Http\Controllers\Admin\RevenueController as AdminRevenueController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\KitchenController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderTrackingController;
use App\Http\Controllers\ReservationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Web Routes (Client / Vitrine)
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/a-propos', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact', [HomeController::class, 'sendContact'])->name('contact.send');

// Menu & Plats
Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');
Route::get('/plat/{slug}', [MenuController::class, 'show'])->name('menu.show');
Route::get('/api/plat/{id}', [MenuController::class, 'detailJson'])->name('api.plat.detail');

// Réservations de table
Route::get('/reserver', [ReservationController::class, 'create'])->name('reservation.create');
Route::post('/reserver', [ReservationController::class, 'store'])->name('reservation.store');
Route::get('/reservation/{code}', [ReservationController::class, 'show'])->name('reservation.confirmation');

// Commande & Panier
Route::get('/commander', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/commander', [CheckoutController::class, 'process'])->name('checkout.process');

// Suivi de commande en temps réel
Route::get('/suivi/{token}', [OrderTrackingController::class, 'show'])->name('suivi.commande');
Route::get('/api/suivi/{token}/statut', [OrderTrackingController::class, 'status'])->name('api.suivi.statut');

// Factures imprimables
Route::get('/facture/{numero}', [InvoiceController::class, 'show'])->name('facture.show');

/*
|--------------------------------------------------------------------------
| Authentification & Espace Client
|--------------------------------------------------------------------------
*/
Route::get('/connexion', [AuthController::class, 'showLogin'])->name('login');
Route::post('/connexion', [AuthController::class, 'login']);
Route::get('/inscription', [AuthController::class, 'showRegister'])->name('register');
Route::post('/inscription', [AuthController::class, 'register']);
Route::post('/deconnexion', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/compte', [AuthController::class, 'profile'])->name('compte.commandes');
});

/*
|--------------------------------------------------------------------------
| Espace Coursier / Livreur (PWA Mobile-First)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:livreur,admin'])->prefix('coursier')->name('coursier.')->group(function () {
    Route::get('/', [DeliveryController::class, 'index'])->name('dashboard');
    Route::get('/livraison/{id}', [DeliveryController::class, 'show'])->name('show');
    Route::post('/livraison/{id}/reclamer', [DeliveryController::class, 'claim'])->name('claim');
    Route::post('/livraison/{id}/en-route', [DeliveryController::class, 'startDelivery'])->name('start');
    Route::post('/livraison/{id}/terminer', [DeliveryController::class, 'completeDelivery'])->name('complete');
    Route::get('/api/poll', [DeliveryController::class, 'apiPoll'])->name('api.poll');
});

/*
|--------------------------------------------------------------------------
| Espace Cuisine / KDS Staff
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:staff,admin'])->prefix('cuisine')->name('cuisine.')->group(function () {
    Route::get('/', [KitchenController::class, 'index'])->name('dashboard');
    Route::post('/commande/{id}/statut', [KitchenController::class, 'updateStatus'])->name('update_status');
    Route::get('/api/poll', [KitchenController::class, 'apiPoll'])->name('api.poll');
});

/*
|--------------------------------------------------------------------------
| Back-Office Administrateur
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard & Alerte temps réel
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/api/nouvelles-commandes', [AdminDashboardController::class, 'checkNewOrders'])->name('api.new_orders');

    // Gestion des Commandes
    Route::get('/commandes', [AdminOrderController::class, 'index'])->name('commandes.index');
    Route::get('/commandes/{id}', [AdminOrderController::class, 'show'])->name('commandes.show');
    Route::post('/commandes/{id}/statut', [AdminOrderController::class, 'updateStatus'])->name('commandes.update_status');
    Route::post('/commandes/{id}/assigner-livreur', [AdminOrderController::class, 'assignDriver'])->name('commandes.assign_driver');

    // Gestion des Menus
    Route::get('/menus', [AdminMenuController::class, 'index'])->name('menus.index');
    Route::get('/menus/plats/nouveau', [AdminMenuController::class, 'createPlat'])->name('menus.plats.create');
    Route::post('/menus/plats', [AdminMenuController::class, 'storePlat'])->name('menus.plats.store');
    Route::get('/menus/plats/{id}/modifier', [AdminMenuController::class, 'editPlat'])->name('menus.plats.edit');
    Route::put('/menus/plats/{id}', [AdminMenuController::class, 'updatePlat'])->name('menus.plats.update');
    Route::post('/menus/plats/{id}/toggle', [AdminMenuController::class, 'togglePlatDisponibilite'])->name('menus.plats.toggle');
    Route::delete('/menus/plats/{id}', [AdminMenuController::class, 'destroyPlat'])->name('menus.plats.destroy');

    // Catégories
    Route::post('/menus/categories', [AdminMenuController::class, 'storeCategory'])->name('menus.categories.store');
    Route::put('/menus/categories/{id}', [AdminMenuController::class, 'updateCategory'])->name('menus.categories.update');
    Route::delete('/menus/categories/{id}', [AdminMenuController::class, 'destroyCategory'])->name('menus.categories.destroy');

    // Formules
    Route::post('/menus/formules', [AdminMenuController::class, 'storeFormule'])->name('menus.formules.store');
    Route::delete('/menus/formules/{id}', [AdminMenuController::class, 'destroyFormule'])->name('menus.formules.destroy');

    // Factures
    Route::get('/factures', [AdminInvoiceController::class, 'index'])->name('factures.index');
    Route::get('/factures/{id}', [AdminInvoiceController::class, 'show'])->name('factures.show');
    Route::post('/factures/{id}/marquer-envoyee', [AdminInvoiceController::class, 'markSent'])->name('factures.mark_sent');

    // Revenus & Statistiques
    Route::get('/revenus', [AdminRevenueController::class, 'index'])->name('revenus.index');

    // Réservations de table
    Route::get('/reservations', [AdminReservationController::class, 'index'])->name('reservations.index');
    Route::post('/reservations/{id}/statut', [AdminReservationController::class, 'updateStatus'])->name('reservations.update_status');
    Route::delete('/reservations/{id}', [AdminReservationController::class, 'destroy'])->name('reservations.destroy');

    // Messages
    Route::get('/messages', [AdminMessageController::class, 'index'])->name('messages.index');
    Route::post('/messages/{id}/lu', [AdminMessageController::class, 'markAsRead'])->name('messages.mark_read');
    Route::delete('/messages/{id}', [AdminMessageController::class, 'destroy'])->name('messages.destroy');

    // Paramètres
    Route::get('/parametres', [AdminSettingController::class, 'index'])->name('parametres.index');
    Route::post('/parametres/general', [AdminSettingController::class, 'updateGeneral'])->name('parametres.update_general');
    Route::post('/parametres/zones', [AdminSettingController::class, 'storeZone'])->name('parametres.zones.store');
    Route::put('/parametres/zones/{id}', [AdminSettingController::class, 'updateZone'])->name('parametres.zones.update');
    Route::delete('/parametres/zones/{id}', [AdminSettingController::class, 'destroyZone'])->name('parametres.zones.destroy');
    Route::post('/parametres/utilisateurs', [AdminSettingController::class, 'storeUser'])->name('parametres.users.store');
    Route::post('/parametres/utilisateurs/{id}/toggle', [AdminSettingController::class, 'toggleUserStatus'])->name('parametres.users.toggle');
});
