<?php
namespace App\Services\Roulette;

use Illuminate\Support\Collection;

interface RouletteStrategy
{
    public function spin(Collection $candidates): ?string;

    /** Indique si le joueur tiré doit être supprimé */
    public function shouldEliminate(): bool;
}

