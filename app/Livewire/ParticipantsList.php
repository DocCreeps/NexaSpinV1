<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;

class ParticipantsList extends Component
{
    /** @var array<int,string> */
    public array $candidates = [];

    #[On('participants-updated')]
    public function updateCandidates(array $candidates): void
    {
        $this->candidates = $candidates;
    }

    public function render()
    {
        return view('livewire.participants-list');
    }

    public function remove(string $name): void
    {
        $this->dispatch('participant-removed', name: $name);
    }

    public function rename(string $old, string $new): void
    {
        $new = trim($new);
        if ($new === '' || mb_strlen($new) < 2) {
            // Pour le toast (événement global), le dispatch simple est correct.
            $this->dispatch('toast', type: 'error', message: 'Nom invalide.');
            return;
        }

        $this->dispatch('participant-renamed', old: $old, new: $new);
    }
}
