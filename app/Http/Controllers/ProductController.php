<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\ProductsExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProductsImport;
use PhpOffice\PhpSpreadsheet\IOFactory;

// use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\Storage;
// use Illuminate\Support\Facades\Hash;
// use Illuminate\Support\Facades\Validator;


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

    /**
     * Exporter la liste des produits au format PDF.
     *
     * @return \Illuminate\Http\Response
     */

    public function exportPDF()
{
    $products = Product::all();

    $pdf = Pdf::loadView('products.pdf', compact('products'));
    return $pdf->download('liste_produits.pdf');
}

public function show($id)
{
    $product = Product::findOrFail($id);
    return view('products.show', compact('product'));
}

// fonction pour exporter les produits au format Excel


public function exportExcel()
{
    return Excel::download(new ProductsExport, 'produits.xlsx');
}

  // fonction pour importer les produits depuis un fichier Excel

  public function importExcel(Request $request)
{
    $request->validate([
        'excel_file' => 'required|file|mimes:xlsx,xls'
    ]);

    $file = $request->file('excel_file');
    $data = IOFactory::load($file->getPathname())
        ->getActiveSheet()
        ->toArray(null, true, true, true);

    foreach (array_slice($data, 1) as $row) {
        Product::create([
            'name' => $row['A'] ?? '',
            'description' => $row['B'] ?? '',
            'price' => $row['C'] ?? 0,
            'quantite' => $row['D'] ?? 0,
        ]);
    }

    return redirect()->route('dashboard')->with('success', 'Produits importés avec succès.');
}


}
