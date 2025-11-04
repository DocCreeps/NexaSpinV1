<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Livewire\Traits\ManagesParticipants;
use App\Livewire\Traits\ManagesRouletteState;
use App\Services\Roulette\ClassicRoulette;

#[Layout('layouts.app')]
class ClassicRouletteComponent extends Component
{
    use ManagesRouletteState, ManagesParticipants;

    protected ?ClassicRoulette $strategy = null;

    public bool $winnerAnnounced = false;


    public function mount(): void
    {
        $this->initializeRouletteState();
        $this->initializeParticipants();
        $this->strategy ??= new ClassicRoulette();
    }

    protected function planSpin(): ?string
    {
        $this->strategy ??= new ClassicRoulette();
        return $this->strategy->spin(collect($this->candidates));
    }

    protected function executeSpinStrategy(): ?string
    {
        $this->strategy ??= new ClassicRoulette();
        return $this->strategy->spin(collect($this->candidates));
    }

    public function spinRoulette(): void
    {
        // Vérification des conditions initiales
        if (count($this->candidates) < 2) {
            session()->flash('error', "Il faut au moins 2 participants pour lancer la roulette.");
            return;
        }

        // 🚀 Blocage si la roue tourne ou si un résultat est déjà affiché
        if ($this->isSpinning || $this->winnerAnnounced) {
            return;
        }

        $this->startSpinRoulette();

        if (!$this->plannedChosenOne) {
            session()->flash('error', "Aucun tirage possible.");
            return;
        }

        // Réinitialisation de l'état
        $this->isSpinning = true;
        $this->winnerAnnounced = false;

        $this->dispatch(
            'spin-start',
            planned: $this->plannedChosenOne,
            candidates: $this->candidates,
            duration: $this->spinDuration
        );
    }

    #[On('roulette-finished')]
    public function rouletteEnded(): void // 🚀 Méthode unique pour gérer la fin du spin
    {
        if (!$this->isSpinning) return;

        $this->chosenOne = $this->plannedChosenOne;
        $this->isSpinning = false;

        // 🚀 L'état de blocage est activé à la fin de l'animation
        $this->winnerAnnounced = true;
        $this->dispatch('show-winner', $this->chosenOne);
    }


    #[On('participant-removed')]
    public function handleRemoveFromList(string $name): void
    {
        $this->removeCandidate($name);
        $this->winnerAnnounced = false; // 🚀 Réinitialisation
        $this->dispatch('refresh-wheel');
    }

    #[On('participant-renamed')]
    public function handleRenameFromList(string $old, string $new): void
    {
        $this->renameCandidate($old, $new);
        $this->winnerAnnounced = false;
        $this->dispatch('refresh-wheel');
    }

    // Ajout de la réinitialisation dans la méthode resetGame
    public function resetGame(): void
    {
        $this->initializeRouletteState();
        $this->winnerAnnounced = false;
    }

    #[On('refresh-parent')]
    public function refreshComponent(): void {}

    public function render()
    {
        return view('livewire.classic', [
            'candidates' => $this->candidates,
            'chosenOne' => $this->chosenOne,
            'isSpinning' => $this->isSpinning,
        ]);
    }
}
