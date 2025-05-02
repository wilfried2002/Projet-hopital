<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Gestion de la facturation') }}
        </h2>
    </x-slot>

    @section('content')
    <div class="p-4">
        {{-- Formulaire de création de facture --}}
        <form action="#" method="POST" class="space-y-4 mb-8">
            @csrf

            <div>
                <label class="block font-medium">Patient</label>
                <select name="patient_id" required class="w-full border p-2 rounded">
                    @foreach($patients as $patient)
                        <option value="{{ $patient->id }}">{{ $patient->nom }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block font-medium">Médecin (facultatif)</label>
                <select name="medecin_id" class="w-full border p-2 rounded">
                    <option value="">-- Aucun --</option>
                    @foreach($medecins as $medecin)
                        <option value="{{ $medecin->id }}">{{ $medecin->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <h3 class="font-semibold mt-4">Produits à facturer :</h3>
                @foreach($products as $product)
                    <div class="flex items-center space-x-2">
                        <input type="checkbox" name="produits[{{ $loop->index }}][id]" value="{{ $product->id }}">
                        <span>{{ $product->nom }} - {{ $product->prix }} F CFA</span>
                        <input type="number" name="produits[{{ $loop->index }}][quantite]" min="1" value="1" class="w-20 border p-1">
                    </div>
                @endforeach
            </div>

            <button type="submit" class="mt-4 px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">Créer la facture</button>
        </form>

        {{-- Liste des factures --}}
        <h2 class="text-xl font-bold mb-4">Liste des factures</h2>

        <table class="table-auto w-full border border-gray-200">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-4 py-2">#</th>
                    <th class="px-4 py-2">Code</th>
                    <th class="px-4 py-2">Patient</th>
                    <th class="px-4 py-2">Total (FCFA)</th>
                    <th class="px-4 py-2">Statut</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoices as $invoice)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $loop->iteration }}</td>
                        <td class="px-4 py-2">{{ $invoice->code }}</td>
                        <td class="px-4 py-2">{{ $invoice->patient->nom ?? 'N/A' }}</td>
                        <td class="px-4 py-2">{{ number_format($invoice->total, 0, ',', ' ') }}</td>
                        <td class="px-4 py-2">
                            <span class="text-sm px-2 py-1 rounded
                                {{ $invoice->statut == 'payee' ? 'bg-green-100 text-green-700' : 
                                   ($invoice->statut == 'annulee' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                                {{ ucfirst($invoice->statut) }}
                            </span>
                        </td>
                        <td class="px-4 py-2">
                            <a href="#" class="text-blue-600 hover:underline">Voir</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">
            {{ $invoices->links() }}
        </div>
    </div>
    @endsection
</x-app-layout>
