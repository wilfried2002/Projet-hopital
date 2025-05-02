<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Exports\ProductsExport;
use App\Imports\ProductsImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\InvoiceController;
use App\Models\Product;
use App\Models\Prescription;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Page d'accueil
Route::get('/', function () {
    return view('welcome');
});

// Dashboard (protégé par auth + vérification email)
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Routes protégées (auth obligatoire)
Route::middleware(['auth'])->group(function () {

    // Gestion du profil utilisateur
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Gestion des produits (CRUD complet)
    //Route::resource('products', ProductController::class);
    Route::resource('products', ProductController::class)->except(['create']);
    
    Route::get('/dashboard/search', [DashboardController::class, 'search'])->name('dashboard.search');
    //
    // Route pour afficher les produits rechercher
    Route::get('/dashboard/search', function (Illuminate\Http\Request $request) {
        $query = $request->query('query');
    
        $products = \App\Models\Product::where('name', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%")
                    ->orWhere('price', 'like', "%{$query}%")
                    ->orWhere('quantite', 'like', "%{$query}%")
                    ->get();
    
        return response()->json($products);
    });

    Route::get('/products/export/pdf', [ProductController::class, 'exportPDF'])->name('products.exportPDF');

    // Route::get('/products/export-excel', function () {
    //     return Excel::download(new ProductsExport, 'produits.xlsx');
    // })->name('products.exportExcel');

    Route::get('/products/export-excel', [ProductController::class, 'exportExcel'])->name('products.exportExcel');
    //Route::post('/products/import-excel', [ProductController::class, 'importExcel'])->name('products.importExcel');

    Route::post('/products/import-excel', [ProductController::class, 'importExcel'])->name('products.importExcel');
    
    //Route::get('/products/import-excel', [ProductController::class, 'importExcel'])->name('products.importExcel');

    // Route pour afficher le formulaire de prescription
    Route::get('/prescriptions/create', [PrescriptionController::class, 'create'])->name('prescriptions.create');
    Route::get('/prescriptions', [PrescriptionController::class, 'index'])->name('prescriptions');
    
    // Affichage du formulaire
    Route::get('/prescriptions', [PrescriptionController::class, 'index'])->name('prescriptions');

    // Traitement du formulaire (à créer ensuite)
    Route::post('/prescriptions', [PrescriptionController::class, 'store'])->name('prescriptions.store');
    Route::get('/factures', [InvoiceController::class, 'index'])->name('invoices.index');

});

require __DIR__.'/auth.php';
