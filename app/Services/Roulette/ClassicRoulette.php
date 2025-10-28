<?php
namespace App\Services\Roulette;

use Illuminate\Support\Collection;

class ClassicRoulette implements RouletteStrategy
{
    public function spin(Collection $candidates): ?string
    {
        if ($candidates->count() < 2) {
            return null;
        }

        return $candidates->random();
    }

    public function shouldEliminate(): bool
    {
        return false;
    }
}
