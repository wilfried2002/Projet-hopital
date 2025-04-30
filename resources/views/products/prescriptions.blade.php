<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Liste des Prescriptions') }}
        </h2>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">

            @if(session('success'))
                <div class="mb-4 text-green-600">{{ session('success') }}</div>
            @endif

            <table class="w-full table-auto border-collapse">
                <thead>
                    <tr class="bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-100 text-left">
                        <th class="p-2">Code</th>
                        <th class="p-2">Médicament</th>
                        <th class="p-2">Posologie</th>
                        <th class="p-2">Durée</th>
                        <th class="p-2">Instruction</th>
                        <th class="p-2">Patient</th>
                        <th class="p-2">Médecin</th>
                        <th class="p-2">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($prescriptions as $prescription)
                        <tr class="border-b dark:border-gray-700">
                            <td class="p-2">{{ $prescription->code }}</td>
                            <td class="p-2">{{ $prescription->medicament }}</td>
                            <td class="p-2">{{ $prescription->posologie }}</td>
                            <td class="p-2">{{ $prescription->duree }}</td>
                            <td class="p-2">{{ $prescription->instruction }}</td>
                            <td class="p-2">{{ $prescription->patient }}</td>
                            <td class="p-2">{{ $prescription->medecin->name ?? 'N/A' }}</td>
                            <td class="p-2">
                                <form action="{{ route('prescriptions.destroy', $prescription) }}" method="POST" onsubmit="return confirm('Confirmer la suppression ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-600 hover:underline">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center p-4 text-gray-500">Aucune prescription enregistrée.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-6">
                {{ $prescriptions->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
