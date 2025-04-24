<div>
    <!-- Barre de recherche dynamique -->
    <!-- Bouton Ajouter -->
    <div class="flex flex-wrap justify-end items-center gap-2 mb-4">
    <!-- Menu déroulant Export / Import -->
    <div x-data="{ open: false }" class="relative">
        <button @click="open = !open"
            class="flex items-center px-4 py-2 bg-yellow-400 text-black font-semibold rounded-md shadow hover:bg-yellow-500 transition">
            📤 Export / Import
            <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
            </svg>
        </button>

        <div x-show="open" @click.away="open = false"
            class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-lg z-50">
            <ul class="py-1 text-sm text-gray-700 dark:text-gray-200">
                <!-- Export PDF -->
                <li>
                    <button wire:click="exportPDF"
                        class="w-full text-left px-4 py-2 hover:bg-yellow-100 dark:hover:bg-gray-600 transition">
                        📄 Exporter PDF
                    </button>
                </li>

                <!-- Import Excel -->
                <li>
                    <label class="w-full block px-4 py-2 cursor-pointer hover:bg-yellow-100 dark:hover:bg-gray-600 transition">
                        📥 Importer Excel
                        <input type="file" wire:model="importFile" wire:change="importExcel"
                               class="hidden" accept=".xlsx, .xls">
                    </label>
                </li>
            </ul>
        </div>
    </div>

    <!-- Bouton Ajouter un produit -->
    <button @click="openCreate()"
        class="inline-flex items-center px-4 py-2 bg-red-600 text-white font-semibold rounded-md shadow hover:bg-red-700 transition">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
        </svg>
        Ajouter un produit
    </button>
</div>
    
    <input type="text"
           wire:model.debounce.300ms="search" 
           placeholder="Rechercher un produit..."
           class="p-2 border border-gray-300 rounded-md w-1/2 lg:w-[33.3333%] mb-4">

    <h3>Liste des produits </h3>
           
    <!-- Liste des produits -->
    <div class="w-full overflow-x-auto rounded-lg shadow-md">
        <table class="min-w-full table-auto bg-white dark:bg-gray-800 border-collapse">
            <thead class="bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm">
                <tr>
                    <th class="px-6 py-3 text-left font-semibold">Nom</th>
                    <th class="px-6 py-3 text-left font-semibold">Description</th>
                    <th class="px-6 py-3 text-left font-semibold">Prix</th>
                    <th class="px-6 py-3 text-left font-semibold">Quantité</th>
                    <th class="px-6 py-3 text-left font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr class="border-t border-gray-300 dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                        <!-- Nom -->
                        <td class="px-6 py-4 text-gray-900 dark:text-white font-medium">
                            {{ $product->name }}
                        </td>

                        <!-- Description -->
                        <td class="px-6 py-4 text-gray-600 dark:text-gray-300 max-w-xs truncate">
                            {{ $product->description }}
                        </td>

                        <!-- Prix -->
                        <td class="px-6 py-4 text-green-600 font-bold">
                            {{ number_format($product->price, 2) }} FCFA
                        </td>

                        <!-- Quantité -->
                        <td class="px-6 py-4">
                            @if($product->quantite >= 5)
                                <span class="text-green-500 font-semibold">✔️ En stock ({{ $product->quantite }})</span>
                            @else
                                <span class="text-red-500 font-semibold">⚠️ Stock d'alerte ({{ $product->quantite }})</span>
                            @endif
                        </td>

                        <!-- Actions -->
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-2">
                                <!-- Modifier -->
                                <button class="inline-flex items-center px-3 py-1.5 bg-blue-500 text-white text-sm rounded hover:bg-blue-600 transition">
                                    🖊️ Modifier
                                </button>

                                <!-- Supprimer -->
                                <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-500 text-white text-sm rounded hover:bg-red-600 transition">
                                        🗑️ Supprimer
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-6 text-center text-gray-500">
                            {{ __("Aucun produit disponible.") }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<!-- Alpine.js - logique du modal -->
<script>
        function productModal() {
    return {
        showModal: false,
        mode: 'create',
        formAction: '{{ route("products.store") }}',
        product: {
            name: '',
            description: '',
            price: '',
            quantite: ''
        },
        search: '',
        produits: @json($products), // injecter les produits initiaux

        init() {
            this.resetForm()
        },

        openCreate() {
            this.mode = 'create'
            this.formAction = '{{ route("products.store") }}'
            this.product = { name: '', description: '', price: '', quantite: '' }
            this.showModal = true
        },

        openEdit(product) {
            this.mode = 'edit'
            this.formAction = `/products/${product.id}`
            this.product = {
                name: product.name,
                description: product.description,
                price: product.price,
                quantite: product.quantite
            }
            this.showModal = true
        },

        resetForm() {
            this.product = { name: '', description: '', price: '', quantite: '' }
        },

    }
}

    </script>
<!-- Pagination -->
 