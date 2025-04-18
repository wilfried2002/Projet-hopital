<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Show the form for creating a new product.
     *
     * @return \Illuminate\View\View
     */

     //afficher le formulaire de création d'un produit
    public function create()
    {

        return view('product.create');
    }

    //enregistrer un nouveau produit

    public function store(Request $request)
{
    // Validation des données
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'price' => 'required|numeric|min:0',
    ]);

    // Création du produit
    $product = new Product();
    $product->name = $validatedData['name'];
    $product->description = $validatedData['description'] ?? '';
    $product->price = $validatedData['price'];
    $product->save();

    // Redirection avec un message
    return redirect()->route('product.create')->with('success', 'Produit créé avec succès !');
}

} 