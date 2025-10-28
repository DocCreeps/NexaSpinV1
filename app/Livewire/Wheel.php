<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;

class Wheel extends Component
{
    public array $candidates = [];
    public bool $isSpinning = false;
    public ?string $plannedChosen = null;

    public function mount(array $candidates = [], bool $isSpinning = false, ?string $plannedChosen = null)
    {
        $this->candidates = $candidates;
        $this->isSpinning = $isSpinning;
        $this->plannedChosen = $plannedChosen;
    }

    #[On('spin-start')]
    public function onSpinStart(string $planned, array $candidates, int $duration): void
    {
        $this->plannedChosen = $planned;
        $this->candidates = $candidates;
        $this->isSpinning = true;
    }
    #[On('participants-updated')]
    public function updateCandidates(array $candidates): void
    {
        // 1. Update the local list
        $this->candidates = $candidates;

        // 2. Ensure the planned winner is still valid, or reset it
        if ($this->plannedChosen && !in_array($this->plannedChosen, $candidates)) {
            $this->plannedChosen = null;
        }
        $this->render();
    }


    public function render()
    {
        return view('livewire.wheel');
    }
}
