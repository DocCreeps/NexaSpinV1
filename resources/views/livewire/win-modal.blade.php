<div x-data="{ open: @entangle('isOpen') }" x-show="open" class="fixed inset-0 flex items-center justify-center z-50 bg-black/50">
    <div class="bg-white rounded-lg shadow-xl p-8 w-96 text-center relative">
        <button @click="open = false; $wire.close()" class="absolute top-2 right-2 text-gray-500 hover:text-gray-800">&times;</button>

        <h2 class="text-3xl font-bold text-green-700 mb-4">🏆 LE GAGNANT EST :</h2>
        <p class="text-2xl font-semibold">{{ $winner }}</p>

        <button @click="open = false; $wire.close()" class="mt-6 px-6 py-3 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
            Fermer
        </button>
    </div>
</div>
