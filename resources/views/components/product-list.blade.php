 @forelse($products as $product)
    <div class="bg-white dark:bg-gray-700 p-4 rounded-lg shadow flex flex-col justify-between h-full transition-transform transform hover:scale-105 hover:shadow-xl">
        <div>
            <h4 class="font-bold text-lg">{{ $product->name }}</h4>
            <p class="text-gray-600 dark:text-gray-300 mt-1 text-truncate">
    {{ $product->description }}
</p>
</p>
            <p class="text-green-600 font-bold mt-2">{{ number_format($product->price, 2) }} FCFA</p>

            {{-- ✅ Affichage dynamique de la quantité --}}
            @if($product->quantite >= 5)
                <p class="text-sm text-green-500 font-semibold mt-1">✔️ En stock ({{ $product->quantite }})</p>
            @else
                <p class="text-sm text-red-500 font-semibold mt-1">⚠️ Stock d'alerte ({{ $product->quantite }})</p>
            @endif
        </div>

        <div class="mt-4 flex justify-end space-x-2">
            <!-- Bouton Modifier -->
            <button @click='openEdit(@json($product))'
                    class="inline-flex items-center px-3 py-1.5 bg-blue-500 text-white text-sm rounded hover:bg-blue-600 transition">
                🖊️ Modifier
            </button>

            <!-- Supprimer -->
            <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?');">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="inline-flex items-center px-3 py-1.5 bg-red-500 text-white text-sm rounded hover:bg-red-600 transition">
                    🗑️ Supprimer
                </button>
            </form>
        </div>
    </div>
@empty
    <p class="text-gray-500">{{ __("Aucun produit disponible.") }}</p>
@endforelse