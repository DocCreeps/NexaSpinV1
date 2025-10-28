<?php

namespace App\Services\Roulette;

use Illuminate\Support\Collection;

class RussianRoulette implements RouletteStrategy
{
    /**
     * @inheritDoc
     */
    public function spin(Collection $candidates): ?string
    {
        if ($candidates->count() < 2) {
            return null;
        }

        // Logique de la Roulette Russe voulue: tirage aléatoire à chaque manche
        // (différenciée de la classique par l'affichage/UX, mais tirage random ici).
        return $candidates->random();

        // Alternative plus "russe" (tirage cyclique si on veut ajouter de l'état dans le Service):
        // $indexToEliminate = (count($candidates) % 3) == 0 ? 0 : 1; // Logique arbitraire
        // return $candidates->get($indexToEliminate);
    }


    /**
     * @inheritDoc
     */
    public function shouldEliminate(): bool
    {
        return true;
    }
}
