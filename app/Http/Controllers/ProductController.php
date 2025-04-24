<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Afficher les produits paginés.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Récupérer les produits avec pagination (par exemple 15 produits par page)
        $products = Product::paginate(10);  // Modifier 15 selon le nombre de produits par page

        // Retourner la vue 'dashboard' avec les produits paginés
        return view('dashboard', compact('products'));

    }

    /**
     * Show the form for creating a new product.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Store a newly created product in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validation des données
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'quantite' => 'required|integer|min:1',
        ]);

        // Création du produit
        Product::create($validatedData);

        // Rediriger vers le dashboard avec un message de succès
        return redirect()->route('dashboard')->with('success', 'Produit créé avec succès !');
    }

    /**
     * Show the form for editing the specified product.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\View\View
     */
    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    /**
     * Update the specified product in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Product $product)
    {
        // Validation des données
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'price' => 'required|numeric|min:0',
            'quantite' => 'required|integer|min:1',
        ]);

        // Mise à jour du produit
        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'quantite' => $request->quantite,
        ]);

        // Rediriger vers le dashboard avec un message de succès
        return redirect()->route('dashboard')->with('success', 'Produit mis à jour avec succès !');
    }

    /**
     * Remove the specified product from storage.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('dashboard')->with('success', 'Produit supprimé avec succès !');
    }

    /**
     * Rechercher des produits par nom.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */

    public function search(Request $request)
    {
        $query = $request->get('query');

        $produits = Produit::where('nom', 'like', "%$query%")->get();

        return response()->json($produits);
    }
}
