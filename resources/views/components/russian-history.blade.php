@props(['eliminated'])

<div class="mt-6 p-4 bg-red-50 border-t-4 border-red-500 rounded-lg shadow-inner">
    <h3 class="text-xl font-bold mb-3 text-red-700">💀 Ordre d'Élimination</h3>

    <ol class="space-y-2">
        {{-- CORRECTION : On boucle directement sur $eliminated sans array_reverse --}}
        @forelse ($eliminated as $index => $candidate)
            <li class="flex items-center p-2 bg-white rounded shadow-sm text-gray-800">
                {{-- L'index commence à 0, donc on ajoute 1 pour l'ordre croissant --}}
                <span class="font-extrabold text-red-600 mr-3 w-6 text-center">#{{ $index + 1 }}</span>
                <span class="flex-grow">{{ $candidate }}</span>
                <span class="text-xs italic text-gray-500">Éliminé</span>
            </li>
        @empty
            <li class="text-gray-500 italic">L'histoire est encore à écrire...</li>
        @endforelse
    </ol>
</div>
