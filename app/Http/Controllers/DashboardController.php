<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class DashboardController extends Controller
{
    public function index()
    {
        $products = Product::latest()->get(); // Récupère les produits
        return view('dashboard', compact('products')); // Passe les produits à la vue
    }


//     public function search(Request $request)
// {
//     $query = $request->get('query');

//     // Tu peux filtrer sur le champ "name" (ou autre si nécessaire)
//     $produits = Produit::where('name', 'like', '%' . $query . '%')->get();

//     // Tu renvoies une réponse JSON
//     return response()->json($produits);
// }
}
