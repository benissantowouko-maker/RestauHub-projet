<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Formule;
use App\Models\Plat;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MenuController extends Controller
{
    public function index(Request $request): View
    {
        $categories = Category::active()->ordered()->get();
        $selectedCategorySlug = $request->get('categorie');
        $search = $request->get('q');
        $tri = $request->get('tri', 'ordre');

        $query = Plat::with('category')->disponible();

        if ($selectedCategorySlug && $selectedCategorySlug !== 'tous') {
            $query->whereHas('category', function ($q) use ($selectedCategorySlug) {
                $q->where('slug', $selectedCategorySlug);
            });
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        match ($tri) {
            'prix_asc' => $query->orderBy('prix_fcfa', 'asc'),
            'prix_desc' => $query->orderBy('prix_fcfa', 'desc'),
            'populaire' => $query->orderBy('est_populaire', 'desc')->orderBy('ordre_affichage', 'asc'),
            default => $query->orderBy('ordre_affichage', 'asc')->orderBy('nom', 'asc'),
        };

        $plats = $query->paginate(12)->withQueryString();
        $formules = Formule::disponible()->get();

        return view('pages.menu', compact('categories', 'plats', 'formules', 'selectedCategorySlug', 'search', 'tri'));
    }

    public function show(string $slug): View
    {
        $plat = Plat::with('category')->where('slug', $slug)->firstOrFail();
        $similaires = Plat::where('categorie_id', $plat->categorie_id)
            ->where('id', '!=', $plat->id)
            ->disponible()
            ->take(3)
            ->get();

        return view('pages.plat-detail', compact('plat', 'similaires'));
    }

    public function detailJson(int $id): JsonResponse
    {
        $plat = Plat::with('category')->findOrFail($id);

        return response()->json([
            'id' => $plat->id,
            'nom' => $plat->nom,
            'description' => $plat->description,
            'prix_fcfa' => $plat->prix_fcfa,
            'prix_formate' => $plat->prix_formate,
            'photo_url' => $plat->photo_url,
            'categorie' => $plat->category->nom,
            'temps_preparation_minutes' => $plat->temps_preparation_minutes,
        ]);
    }
}
