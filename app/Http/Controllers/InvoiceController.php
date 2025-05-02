<?php


namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Product;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;



class InvoiceController extends Controller
{
    public function index()
{
    $invoices = Invoice::with('patient')->latest()->paginate(10); // 10 factures par page
    return view('products.invoices', compact('invoices'));
}

    public function create()
    {
        $patients = Patient::all();
        $products = Product::all(); // médicaments / services disponibles
        $medecins = User::where('role', 'medecin')->get(); // si applicable
        return view('invoices.create', compact('patients', 'products', 'medecins'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'medecin_id' => 'nullable|exists:users,id',
            'produits' => 'required|array',
            'produits.*.id' => 'required|exists:products,id',
            'produits.*.quantite' => 'required|integer|min:1',
        ]);

        // Créer la facture
        $invoice = Invoice::create([
            'code' => 'INV-' . strtoupper(uniqid()),
            'patient_id' => $request->patient_id,
            'medecin_id' => $request->medecin_id,
            'total' => 0, // sera recalculé
            'statut' => 'en_attente',
        ]);

        $total = 0;

        // Ajouter les produits sélectionnés
        foreach ($request->produits as $produit) {
            $product = Product::findOrFail($produit['id']);
            $quantite = $produit['quantite'];
            $sousTotal = $product->prix * $quantite;
            $total += $sousTotal;

            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'description' => $product->nom,
                'prix_unitaire' => $product->prix,
                'quantite' => $quantite,
            ]);
        }

        // Mise à jour du total de la facture
        $invoice->update(['total' => $total]);

        return redirect()->route('invoices.index')->with('success', 'Facture créée avec succès.');
    }
    
}
