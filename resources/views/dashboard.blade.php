<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Gestion de la pharmacie ') }}
            </h2>
        </div>
    </x-slot>

<div class="py-12" x-data="tabNavigation()">
    <div class="py-12" x-data="productModal()" x-init="init()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                <!-- Notification de succès -->
                 
                @if(session('success'))
                        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 2000)" 
                             class="fixed bottom-5 right-5 bg-green-500 text-white p-4 rounded-lg shadow-lg mb-4 z-50 transition-all">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Notification d'erreur -->
                    @if(session('error'))
                        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 2000)" 
                             class="fixed bottom-5 right-5 bg-red-500 text-white p-4 rounded-lg shadow-lg mb-4 z-50 transition-all">
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    <div 
    x-data="{
        currentTab: 'produits',
        setTab(tab) { this.currentTab = tab; lucide.createIcons(); }
    }"
    x-init="lucide.createIcons()"
    class="flex items-center mb-6"
>
    <!-- Prescriptions -->
    <a href="{{ route('prescriptions') }}"
    class="flex items-center space-x-2 px-4 py-2 rounded-l bg-blue-500 text-white hover:bg-blue-600 transition-colors duration-200">
    <i data-lucide="file-clock" class="w-5 h-5"></i>
    <span>Prescriptions en cours</span>
</a>

    <!-- Analyses -->
    <a href="#"
    class="flex items-center space-x-2 px-4 py-2 bg-green-500 text-white hover:bg-green-600 transition-colors duration-200">
    <i data-lucide="receipt" class="w-5 h-5"></i>
    <span>Facturation</span>
</a>


    <!-- Historiques -->
    <a href="#"
        class="flex items-center space-x-2 px-4 py-2 rounded-r bg-yellow-500 text-white hover:bg-yellow-600 transition-colors duration-200">
        <i data-lucide="history" class="w-5 h-5"></i>
        <span>Historiques</span>
    </a>
</div>
                    <div class="flex justify-end mb-4">
                        <!-- // Barre de recherche -->

                        <input 
    type="text"
    x-model="search"
    @input="fetchProduits"
    placeholder="Rechercher un produit..."
    class="p-2 border border-gray-300 rounded-md w-1/2 lg:w-[33.3333%] mr-[110px]"
>
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
                <a href="{{ route('products.exportPDF') }}"
                        class="w-full text-left px-4 py-2 hover:bg-yellow-100 dark:hover:bg-gray-600 transition block">
                        📄 Exporter PDF
                </a>

                </li>
                <!-- Export Excel -->

                <!-- <li>
                    <a href="{{ route('products.exportExcel') }}"
                        class="w-full text-left px-4 py-2 hover:bg-yellow-100 dark:hover:bg-gray-600 transition block">
                        📊 Exporter Excel
                    </a>
                </li> -->
                


                <!-- Importer Excel -->
                <!-- 1. Bouton dans le menu -->
                <li>
    <form action="{{ route('products.importExcel') }}" method="POST" enctype="multipart/form-data" class="px-4 py-2">
        @csrf
        <label for="excelFile" class="cursor-pointer block hover:bg-yellow-100 dark:hover:bg-gray-600 px-2 py-1 rounded transition">
            📊 Importer Excel
            <input type="file" name="excel_file" id="excelFile" class="hidden" onchange="this.form.submit()">
        </label>
    </form>
</li>




                </li>
                <!-- Importer PDF -->
                <!-- <li>
                <a href="#"
                        class="w-full text-left px-4 py-2 hover:bg-yellow-100 dark:hover:bg-gray-600 transition block">
                        📄 Importer PDF
                </a>

                </li> -->
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


                        <!-- Modal -->
                        <div x-show="showModal"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 scale-90"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-90"
                             class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
                             style="display: none;">

                            <div @click.away="showModal = false"
                                 class="bg-white dark:bg-gray-800 rounded-lg p-6 w-full max-w-2xl shadow-lg relative">

                                <button @click="showModal = false"
                                        class="absolute top-3 right-3 text-gray-500 hover:text-gray-800 dark:hover:text-gray-200">
                                    ✖
                                </button>

                                <h2 class="text-xl font-bold text-gray-800 dark:text-white mb-4"
                                    x-text="mode === 'create' ? 'Ajouter un produit' : 'Modifier le produit'"></h2>

                                <!-- Formulaire -->
                                <form :action="formAction" method="POST">
                                    @csrf
                                    <template x-if="mode === 'edit'">
                                        <input type="hidden" name="_method" value="PUT">
                                    </template>

                                    <div class="mb-4">
                                        <x-input-label for="name" :value="__('Nom du produit')" />
                                        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                                                      x-model="product.name" required />
                                    </div>

                                    <div class="mb-4">
                                        <x-input-label for="description" :value="__('Description')" />
                                        <textarea id="description" name="description"
                                                  class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                                  x-model="product.description"></textarea>
                                    </div>

                                    <div class="mb-4">
                                        <x-input-label for="quantite" :value="__('Quantité')" />
                                        <x-text-input id="quantite" class="block mt-1 w-full" type="number"
                                                      name="quantite" x-model="product.quantite" required />
                                    </div>

                                    <div class="mb-4">
                                        <x-input-label for="price" :value="__('Prix')" />
                                        <x-text-input id="price" class="block mt-1 w-full" type="number"
                                                      name="price" step="0.01" x-model="product.price" required />
                                    </div>

                                    <div class="flex justify-end mt-4 space-x-2">
                                        <button type="button" @click="showModal = false"
                                                class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400">
                                            Annuler
                                        </button>
                                        <x-primary-button>
                                            <span x-text="mode === 'create' ? 'Créer' : 'Mettre à jour'"></span>
                                        </x-primary-button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        
                    </div>
                    
                    <h3 class="text-lg font-semibold mb-4">{{ __("Liste des produits") }}</h3>

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
        <tbody id="product-table-body">
    @foreach($products as $product)
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
            </td>
        </tr>
    @endforeach
</tbody>
        </table>    
                </div>
            </div>
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

        

        fetchProduits() {
    const tableBody = document.getElementById('product-table-body');

    // Appel AJAX vers la route de recherche
    fetch(`/dashboard/search?query=${encodeURIComponent(this.search)}`)
        .then(response => response.json())
        .then(data => {
            this.produits = data;

            // Nettoyer le contenu actuel du tableau
            tableBody.innerHTML = '';

            // Vérifier si aucun produit trouvé
            if (data.length === 0) {
                const emptyRow = document.createElement('tr');
                emptyRow.innerHTML = `
                    <td colspan="5" class="px-6 py-6 text-center text-gray-500">
                        Aucun produit trouvé pour cette recherche.
                    </td>
                `;
                tableBody.appendChild(emptyRow);
                return; // On arrête ici
            }

            // Recréer chaque ligne
            data.forEach(product => {
                const row = document.createElement('tr');
                row.className = "border-t hover:bg-gray-100 dark:hover:bg-gray-700 transition";

                row.innerHTML = `
                    <td class="px-6 py-4 text-gray-900 dark:text-white font-medium">${product.name}</td>
                    <td class="px-6 py-4 text-gray-600 dark:text-gray-300 max-w-xs truncate">${product.description}</td>
                    <td class="px-6 py-4 text-green-600 font-bold">${parseFloat(product.price).toFixed(2)} FCFA</td>
                    <td class="px-6 py-4">
                        ${product.quantite >= 5
                            ? `<span class="text-green-500 font-semibold">✔️ En stock (${product.quantite})</span>`
                            : `<span class="text-red-500 font-semibold">⚠️ Stock d'alerte (${product.quantite})</span>`}
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex flex-wrap gap-2">
                            <button onclick='productModal().openEdit(${JSON.stringify(product)})'
                                    class="inline-flex items-center px-3 py-1.5 bg-blue-500 text-white text-sm rounded hover:bg-blue-600 transition">
                                🖊️ Modifier
                            </button>

                            <form action="/products/${product.id}" method="POST" onsubmit="return confirm('Supprimer ce produit ?');">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit"
                                        class="inline-flex items-center px-3 py-1.5 bg-red-500 text-white text-sm rounded hover:bg-red-600 transition">
                                    🗑️ Supprimer
                                </button>
                            </form>
                        </div>
                    </td>
                `;

                tableBody.appendChild(row);
            });
        })
        .catch(error => {
            console.error("Erreur de recherche :", error);
        });
}

    

    }
}

    </script>

<script>
    lucide.createIcons();
</script>


</x-app-layout>
