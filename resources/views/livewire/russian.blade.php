<div x-data="{
         message: null,
         type: null,
         delay: @js($autoSpinDelay), // Est 2000ms (2s)
         timeoutId: null,
         countdown: 0,
         startCountdown() {
             this.countdown = this.delay / 1000;
             const intervalId = setInterval(() => {
                 this.countdown--;
                 if (this.countdown <= 0) {
                     clearInterval(intervalId);
                     this.countdown = 0;
                 }
             }, 1000);
         }
     }"
     x-on:roulette-finish.window="$wire.rouletteEnded()"
     x-on:show-notification.window="
        message = $event.detail.message;
        type = $event.detail.type;
        setTimeout(() => { message = null; type = null; }, 5000);
     "
     x-on:plan-next-spin.window="
        // 🟢 CORRECTION : Appel de la fonction de décompte
        startCountdown();

        if (timeoutId) clearTimeout(timeoutId);

        timeoutId = setTimeout(() => {
            // Relance automatique après 2s
            $wire.call('startNextSpin');
        }, delay);
     ">

    <h1 class="text-4xl font-bold mb-6 text-red-600">Roulette - Mode : <span class="uppercase">RUSSE - AUTO</span></h1>

    {{-- Affichage des messages via Alpine.js/Dispatch --}}
    <div x-show="message"
         x-transition
         :class="{
            'p-4 mb-4 text-sm rounded-lg': true,
            'text-red-800 bg-red-50': type === 'error',
            'text-green-800 bg-green-50': type === 'success',
            'text-yellow-800 bg-yellow-50': type === 'warning' || type === 'info'
         }"
         role="alert">
        <span x-text="message"></span>
    </div>

    {{-- Ajout de participant --}}
    <div class="mb-6 flex gap-2">
        <input wire:model.defer="newCandidateName"
               wire:keydown.enter.prevent="addCandidate"
               type="text"
               placeholder="{{ $gameStarted ? 'Ajout bloqué après le lancement 🔒' : 'Nom du participant' }}"
               class="flex-1 p-3 border border-gray-300 rounded @error('newCandidateName') border-red-500 @enderror disabled:bg-gray-100"
            @disabled($gameStarted || $winner)
        >

        <button type="button"
                wire:click="addCandidate"
                class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 disabled:bg-gray-400 disabled:cursor-not-allowed"
            @disabled($gameStarted || $winner)>
            Ajouter
        </button>
    </div>

    <hr class="my-6">

    {{-- ⬆️ SECTION 1 : LA ROUE ET LES CONTRÔLES (PLACÉS AU-DESSUS) ⬆️ --}}
    <div class="mb-8">
        <h2 class="text-2xl font-semibold mb-4 text-gray-700 text-center">Roue d'Élimination</h2>

        <div class="max-w-xl mx-auto">
            <livewire:wheel
                :candidates="$candidates"
                :is-spinning="$isSpinning"
                wire:key="wheel-r{{ count($candidates) }}"
            />

            <div class="my-4"></div>

            @if (!$winner)

                {{-- Affichage du Décompte --}}
                <template x-if="countdown > 0">
                    <div class="w-full px-6 py-4 bg-yellow-500 text-white text-xl font-bold rounded-lg shadow-xl text-center">
                        Prochain tirage dans <span x-text="countdown"></span> s...
                    </div>
                </template>

                {{-- Bouton de Lancement Initial / Statut de Spin --}}
                <template x-if="countdown === 0">
                    <button type="button" wire:click="spinRoulette"
                            @disabled(count($candidates) < 2 || $isSpinning || $gameStarted)
                            class="w-full px-6 py-4 bg-red-600 text-white text-xl font-bold rounded-lg shadow-xl hover:bg-red-700 disabled:bg-gray-400 disabled:cursor-not-allowed transition duration-150 ease-in-out">

                        <span x-show="!$wire.isSpinning">
                            LANCER l'ÉLIMINATION ! ({{ count($candidates) }} restants)
                        </span>

                        <span x-show="$wire.isSpinning">
                            Tirage en Cours...
                        </span>
                    </button>
                </template>

                <div class="flex gap-3 mt-3">
                    <button type="button" wire:click="resetGame" class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">Réinitialiser</button>
                </div>
            @else
                {{-- 🏆 RETOUR À L'AFFICHAGE DU VAINQUEUR SOUS LA ROUE 🏆 --}}
                <div class="text-center mt-4">
                    <div class="text-center p-8 bg-green-100 rounded-xl shadow-lg mb-4">
                        <h2 class="text-4xl font-extrabold text-green-700 animate-pulse">🏆 LE GAGNANT EST : {{ $winner }} ! 🏆</h2>
                    </div>
                    <button wire:click="resetGame" class="px-6 py-3 bg-gray-500 text-white rounded-lg hover:bg-gray-600">Recommencer une partie</button>
                </div>
            @endif
        </div>
    </div>
    {{-- ⬇️ FIN DE LA ROUE ⬇️ --}}


    {{-- ⬅️ SECTION 2 : LISTES EN GRILLE (PLACÉES EN DESSOUS) ➡️ --}}
    <div class="grid md:grid-cols-2 gap-8">

        {{-- COLONNE 1 : LISTE DES PARTICIPANTS (GAUCHE) --}}
        <div>
            <h2 class="text-2xl font-semibold mb-4 text-gray-700">Participants ({{ count($candidates) }})</h2>
            <livewire:participants-list
                :candidates="$candidates"
                wire:key="list-{{ count($candidates) }}"
            />
        </div>

        {{-- COLONNE 2 : HISTORIQUE D'ÉLIMINATION (DROITE) --}}
        <div>
            <h2 class="text-2xl font-semibold mb-4 text-gray-700">Historique des Éliminés</h2>
            <x-russian-history :eliminated="$eliminated" />
        </div>
    </div>
    {{-- ⬇️ FIN DES LISTES ⬇️ --}}

</div>
