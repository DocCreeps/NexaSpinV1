<?php
namespace App\Livewire\Traits;

use Livewire\Component;

trait ManagesRouletteState
{
    public ?string $chosenOne = null;
    public bool $isSpinning = false;
    public ?string $plannedChosenOne = null;
    protected int $spinDuration = 10000;

    protected function initializeRouletteState(): void
    {
        $this->chosenOne = null;
        $this->plannedChosenOne = null;
        $this->isSpinning = false;
    }

    public function startSpinRoulette(): void
    {
        if (!isset($this->candidates) || count($this->candidates) < 2) {
            session()->flash('error', "Il faut au moins 2 candidats pour lancer la roulette.");
            return;
        }

        if ($this->isSpinning) return;

        $this->isSpinning = true;
        $this->chosenOne = null;
        $this->plannedChosenOne = $this->planSpin();

    }

    public function rouletteEnded(): void
    {
        if (!$this->isSpinning) return;

        $this->chosenOne = $this->plannedChosenOne;
        $this->isSpinning = false;

    }

    abstract protected function planSpin(): ?string;
    abstract protected function executeSpinStrategy(): ?string;
}

