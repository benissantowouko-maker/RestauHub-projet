<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Formule;
use App\Models\Plat;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class MenuController extends Controller
{
    public function index(): View
    {
        $categories = Category::ordered()->withCount('plats')->get();
        $plats = Plat::with('category')->ordered()->paginate(20);
        $formules = Formule::all();

        return view('admin.menus.index', compact('categories', 'plats', 'formules'));
    }

    // --- PLATS ---
    public function createPlat(): View
    {
        $categories = Category::ordered()->get();
        return view('admin.menus.plat-form', [
            'plat' => new Plat(),
            'categories' => $categories,
            'isEdit' => false,
        ]);
    }

    public function storePlat(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'categorie_id' => 'required|exists:categories,id',
            'nom' => 'required|string|max:150',
            'description' => 'nullable|string|max:1000',
            'prix_fcfa' => 'required|numeric|min:100',
            'photo_url' => 'nullable|string|max:500',
            'disponible' => 'nullable|boolean',
            'est_populaire' => 'nullable|boolean',
            'temps_preparation_minutes' => 'nullable|integer|min:1',
            'ordre_affichage' => 'nullable|integer',
        ]);

        $baseSlug = Str::slug($validated['nom']);
        $slug = $baseSlug;
        $counter = 1;
        while (Plat::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter++;
        }

        Plat::create([
            ...$validated,
            'slug' => $slug,
            'disponible' => $request->has('disponible'),
            'est_populaire' => $request->has('est_populaire'),
            'temps_preparation_minutes' => $validated['temps_preparation_minutes'] ?? 20,
            'ordre_affichage' => $validated['ordre_affichage'] ?? 0,
        ]);

        return redirect()->route('admin.menus.index')->with('success', "Le plat « {$validated['nom']} » a été ajouté avec succès.");
    }

    public function editPlat(int $id): View
    {
        $plat = Plat::findOrFail($id);
        $categories = Category::ordered()->get();

        return view('admin.menus.plat-form', [
            'plat' => $plat,
            'categories' => $categories,
            'isEdit' => true,
        ]);
    }

    public function updatePlat(Request $request, int $id): RedirectResponse
    {
        $plat = Plat::findOrFail($id);

        $validated = $request->validate([
            'categorie_id' => 'required|exists:categories,id',
            'nom' => 'required|string|max:150',
            'description' => 'nullable|string|max:1000',
            'prix_fcfa' => 'required|numeric|min:100',
            'photo_url' => 'nullable|string|max:500',
            'disponible' => 'nullable|boolean',
            'est_populaire' => 'nullable|boolean',
            'temps_preparation_minutes' => 'nullable|integer|min:1',
            'ordre_affichage' => 'nullable|integer',
        ]);

        $plat->update([
            ...$validated,
            'disponible' => $request->has('disponible'),
            'est_populaire' => $request->has('est_populaire'),
            'temps_preparation_minutes' => $validated['temps_preparation_minutes'] ?? 20,
            'ordre_affichage' => $validated['ordre_affichage'] ?? 0,
        ]);

        return redirect()->route('admin.menus.index')->with('success', "Le plat « {$plat->nom} » a été mis à jour avec succès.");
    }

    public function togglePlatDisponibilite(int $id): JsonResponse
    {
        $plat = Plat::findOrFail($id);
        $plat->disponible = ! $plat->disponible;
        $plat->save();

        return response()->json([
            'success' => true,
            'disponible' => $plat->disponible,
            'message' => $plat->disponible ? 'Plat marqué disponible.' : 'Plat marqué en rupture de stock.',
        ]);
    }

    public function destroyPlat(int $id): RedirectResponse
    {
        $plat = Plat::findOrFail($id);
        $nom = $plat->nom;
        $plat->delete();

        return back()->with('success', "Le plat « {$nom} » a été supprimé.");
    }

    // --- CATEGORIES ---
    public function storeCategory(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'icone' => 'nullable|string|max:50',
            'photo_url' => 'nullable|string|max:500',
            'ordre_affichage' => 'nullable|integer',
        ]);

        $baseSlug = Str::slug($validated['nom']);
        $slug = $baseSlug;
        $counter = 1;
        while (Category::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter++;
        }

        Category::create([
            ...$validated,
            'slug' => $slug,
            'active' => true,
            'ordre_affichage' => $validated['ordre_affichage'] ?? 0,
        ]);

        return back()->with('success', "La catégorie « {$validated['nom']} » a été créée.");
    }

    public function updateCategory(Request $request, int $id): RedirectResponse
    {
        $category = Category::findOrFail($id);
        $validated = $request->validate([
            'nom' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'icone' => 'nullable|string|max:50',
            'photo_url' => 'nullable|string|max:500',
            'ordre_affichage' => 'nullable|integer',
            'active' => 'nullable|boolean',
        ]);

        $category->update([
            ...$validated,
            'active' => $request->has('active'),
            'ordre_affichage' => $validated['ordre_affichage'] ?? 0,
        ]);

        return back()->with('success', "La catégorie « {$category->nom} » a été modifiée.");
    }

    public function destroyCategory(int $id): RedirectResponse
    {
        $category = Category::findOrFail($id);
        $nom = $category->nom;
        $category->delete();

        return back()->with('success', "La catégorie « {$nom} » et ses plats associés ont été supprimés.");
    }

    // --- FORMULES ---
    public function storeFormule(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:150',
            'description' => 'nullable|string|max:500',
            'composants' => 'required|string|max:1000',
            'prix_fcfa' => 'required|numeric|min:100',
            'photo_url' => 'nullable|string|max:500',
        ]);

        $slug = Str::slug($validated['nom']) . '-' . rand(100, 999);

        Formule::create([
            ...$validated,
            'slug' => $slug,
            'disponible' => true,
        ]);

        return back()->with('success', "La formule « {$validated['nom']} » a été créée.");
    }

    public function destroyFormule(int $id): RedirectResponse
    {
        $formule = Formule::findOrFail($id);
        $nom = $formule->nom;
        $formule->delete();

        return back()->with('success', "La formule « {$nom} » a été supprimée.");
    }
}
