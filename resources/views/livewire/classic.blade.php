<div x-data="{}" x-on:roulette-finished.window="$wire.rouletteEnded()">

    <h1 class="text-4xl font-bold mb-6 text-indigo-600">
        Roulette - Mode : <span class="uppercase">CLASSIC</span>
    </h1>

    {{-- Formulaire d'ajout de participant --}}
    <div class="flex flex-col gap-2 mb-4">
        <div class="flex gap-2">
            <input type="text"
                   wire:model="newCandidateName"
                   wire:keydown.enter="addCandidate"
                   placeholder="Nom du participant"
                   class="border rounded px-2 py-1 flex-1"/>
            <button type="button"
                    wire:click="addCandidate"
                    class="bg-blue-500 text-white px-4 py-1 rounded hover:bg-blue-600 transition-colors">
                Ajouter
            </button>
        </div>
        @error('newCandidateName')
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <hr class="my-6">

    {{-- Affichage de la roulette et du bouton --}}
    <div class="grid md:grid-cols-2 gap-8">
        <div>
            {{-- Composant Livewire pour la roue --}}
            <livewire:wheel
                :candidates="$candidates"
                :is-spinning="$isSpinning"
                :planned-chosen="$plannedChosenOne"
                wire:key="wheel-c{{ count($candidates) }}"
            />

            {{-- Bouton pour lancer la roulette --}}
            <button type="button"
                    wire:click="spinRoulette"
                    @disabled(count($candidates) < 2 || $isSpinning || $winnerAnnounced)
                    class="w-full px-6 py-4 bg-red-600 text-white text-xl font-bold rounded-lg shadow-xl hover:bg-red-700 disabled:bg-gray-400 disabled:cursor-not-allowed transition duration-150 ease-in-out">
                {{ $isSpinning ? 'Roulette en cours...' : 'Lancer la roue (' . count($candidates) . ' participants)' }}
            </button>

            @if(count($candidates) < 2)
                <p class="mt-2 text-sm text-gray-500">
                    Ajoutez au moins 2 participants pour lancer la roue.
                </p>
            @endif
        </div>

        {{-- Liste des participants --}}
        <div>
            <h2 class="text-2xl font-semibold mb-4 text-gray-700">
                Participants ({{ count($candidates) }})
            </h2>
            <livewire:participants-list
                :candidates="$candidates"


            wire:key="list-{{ count($candidates) }}"
            />
        </div>
    </div>

    {{-- Affichage du gagnant --}}
    @if($chosenOne)
        <div class="text-center p-8 bg-green-100 rounded-xl shadow-lg mt-8">
            <h2 class="text-4xl font-extrabold text-green-700 animate-pulse">
                🏆 LE GAGNANT EST : {{ $chosenOne }} ! 🏆
            </h2>
            <button wire:click="resetGame"
                    class="mt-4 px-6 py-3 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                Recommencer une partie
            </button>
        </div>
    @endif

</div>
