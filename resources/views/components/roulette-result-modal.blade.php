@props(['chosenOne'])

<div
    {{-- On utilise Livewire pour déterminer si le résultat doit être affiché --}}
    x-data="{ show: @entangle('chosenOne').defer }"
    x-show="show"
    x-transition:enter="ease-out duration-300"
    x-transition:enter-start="opacity-0 scale-90"
    x-transition:enter-end="opacity-100 scale-100"
    x-transition:leave="ease-in duration-200"
    x-transition:leave-start="opacity-100 scale-100"
    x-transition:leave-end="opacity-0 scale-90"
    class="fixed inset-0 z-50 overflow-y-auto"
    style="display: none;"
>
    {{-- Overlay sombre --}}
    <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

    {{-- Contenu de la modal --}}
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="relative inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">

            @if ($chosenOne)
                <div class="text-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                        <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.332 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <h3 class="mt-4 text-3xl leading-6 font-extrabold text-gray-900" id="modal-title">
                        ÉLIMINATION ! 😱
                    </h3>
                    <div class="mt-2">
                        <p class="text-xl font-semibold text-red-600 animate-pulse">
                            {{ $chosenOne }} est éliminé(e) de la roulette.
                        </p>
                    </div>
                </div>
            @endif

            <div class="mt-5 sm:mt-6">
                {{-- Le bouton ferme la modal, Livewire gère la prochaine étape automatiquement --}}
                <button type="button" @click="show = false" class="inline-flex justify-center w-full rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:text-sm">
                    Manche Suivante
                </button>
            </div>
        </div>
    </div>
</div>
