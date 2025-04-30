<form action="{{ route('invoices.store') }}" method="POST">
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Gestion de la facturation') }}
        </h2>
    </x-slot>

@csrf

    <label>Patient</label>
    <select name="patient_id" required>
        @foreach($patients as $patient)
            <option value="{{ $patient->id }}">{{ $patient->nom }}</option>
        @endforeach
    </select>

    <label>Médecin (facultatif)</label>
    <select name="medecin_id">
        <option value="">-- Aucun --</option>
        @foreach($medecins as $medecin)
            <option value="{{ $medecin->id }}">{{ $medecin->name }}</option>
        @endforeach
    </select>

    <h3>Produits à facturer :</h3>
    @foreach($products as $product)
        <div>
            <input type="checkbox" name="produits[{{ $loop->index }}][id]" value="{{ $product->id }}">
            {{ $product->nom }} - {{ $product->prix }} F CFA
            <input type="number" name="produits[{{ $loop->index }}][quantite]" min="1" value="1">
        </div>
    @endforeach

    <button type="submit">Créer la facture</button>
</form>
