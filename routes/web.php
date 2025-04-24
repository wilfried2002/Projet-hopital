<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

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
    
});

require __DIR__.'/auth.php';
