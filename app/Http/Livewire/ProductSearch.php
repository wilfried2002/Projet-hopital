<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Product;

class ProductSearch extends Component
{
    public $search = '';  // Variable pour stocker la recherche

    // Récupérer les produits en fonction de la recherche
    public function render()
    {
        // Si un terme de recherche est saisi, filtre les produits, sinon récupère tous les produits
        $products = Product::when($this->search, function ($query) {
            return $query->where('name', 'like', '%' . $this->search . '%')
                         ->orWhere('description', 'like', '%' . $this->search . '%')
                         ->orWhere('price', 'like', '%' . $this->search . '%')
                         ->orWhere('quantite', 'like', '%' . $this->search . '%');
        })->get();

        return view('livewire.product-search', compact('products'));
    }
}
