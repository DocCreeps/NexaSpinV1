<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Livewire\Traits\ManagesParticipants;
use App\Livewire\Traits\ManagesRouletteState;
use App\Services\Roulette\EliminationRoulette;
use Illuminate\Support\Collection;

#[Layout('layouts.app')]
class EliminationRouletteComponent extends Component
{
    use ManagesRouletteState, ManagesParticipants;

    protected ?EliminationRoulette $strategy = null;

    public ?string $winner = null;

    public bool $gameStarted = false;

    // Délai fixé à 2 secondes (2000 ms)
    public int $autoSpinDelay = 2000;

    public function mount(): void
    {
        $this->initializeRouletteState();
        $this->initializeParticipants();
        $this->strategy ??= new EliminationRoulette();
        $this->gameStarted = false;
    }

    protected function planSpin(): ?string
    {
        $this->strategy ??= new EliminationRoulette();
        return $this->strategy->spin(collect($this->candidates));
    }

    protected function executeSpinStrategy(): ?string
    {
        $eliminatedCandidate = $this->plannedChosenOne;
        $this->strategy ??= new EliminationRoulette();

        if ($eliminatedCandidate && $this->strategy->shouldEliminate()) {
            $this->candidates = array_values(array_filter($this->candidates, fn($c) => $c !== $eliminatedCandidate));
            $this->eliminated[] = $eliminatedCandidate;
            $this->dispatch('participants-updated', candidates: $this->candidates);
        }

        $this->plannedChosenOne = null;
        return $eliminatedCandidate;
    }

    public function spinRoulette(): void
    {
        if (count($this->candidates) < 2) {
            $this->dispatch('show-notification', type: 'error', message: "Il faut au moins 2 participants pour lancer l'élimination.");
            return;
        }

        if ($this->isSpinning) {
            return;
        }

        $this->plannedChosenOne = $this->planSpin();

        if (!$this->plannedChosenOne) {
            $this->dispatch('show-notification', type: 'error', message: "Aucun tirage possible.");
            return;
        }

        $this->isSpinning = true;
        $this->gameStarted = true;

        $this->dispatch(
            'spin-start',
            planned: $this->plannedChosenOne,
            candidates: $this->candidates,
            duration: $this->spinDuration
        );
    }

    #[On('roulette-finished')]
    public function rouletteEnded(): void
    {
        if (!$this->isSpinning) return;

        $eliminatedCandidate = $this->executeSpinStrategy();
        $this->chosenOne = $eliminatedCandidate;
        $this->isSpinning = false;

        if (count($this->candidates) <= 1) {
            $finalWinner = array_shift($this->candidates);

            if ($finalWinner) {
                $this->winner = $finalWinner;
                $this->dispatch('show-notification', type: 'success', message: "🏆 LE GRAND GAGNANT EST : {$finalWinner} ! Le jeu est terminé.");
            } else {
                $this->dispatch('show-notification', type: 'info', message: "Le dernier participant a été éliminé. Jeu terminé sans vainqueur désigné.");
            }

            $this->chosenOne = null;
        } else {
            $this->dispatch('show-notification', type: 'warning', message: "{$eliminatedCandidate} a été éliminé(e) !");

            // Mode Auto par défaut : Relance toujours si la partie n'est pas finie
            // 🚨 Déclenche l'événement pour la vue Blade, qui s'occupe du timer.
            $this->dispatch('plan-next-spin');
        }
    }

    // 🚨 Cette méthode est maintenant appelée DIRECTEMENT par $wire.call('startNextSpin')
    public function startNextSpin(): void
    {
        // On vérifie s'il reste au moins 2 candidats pour relancer
        if (count($this->candidates) >= 2) {
            $this->spinRoulette();
        }
    }

    #[On('participant-added')]
    public function onParticipantAdded(string $name): void
    {
        $this->newCandidateName = $name;
        $this->addCandidate();
    }

    #[On('participant-removed')]
    public function handleRemoveFromList(string $name): void
    {
        $this->removeCandidate($name);
        $this->dispatch('refresh-wheel');
    }

    #[On('participant-renamed')]
    public function handleRenameFromList(string $old, string $new): void
    {
        $this->renameCandidate($old, $new);
        $this->dispatch('refresh-wheel');
    }

    public function resetGame(): void
    {
        $this->initializeRouletteState();
        $this->initializeParticipants();
        $this->plannedChosenOne = null;
        $this->gameStarted = false;
        $this->dispatch('participants-updated', candidates: $this->candidates);
        $this->dispatch('show-notification', type: 'info', message: 'Le jeu a été réinitialisé.');
    }

    #[On('refresh-parent')]
    public function refreshComponent(): void {}

    public function render()
    {
        return view('livewire.elimination', [
            'winner'     => $this->winner,
            'candidates' => $this->candidates,
            'isSpinning' => $this->isSpinning,
            'eliminated' => $this->eliminated,
        ]);
    }
}
