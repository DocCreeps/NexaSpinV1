<?php

namespace App\Livewire\Traits;

use Livewire\Attributes\Rule;
use Livewire\Component;

trait ManagesParticipants
{
    /** @var array<int,string> */
    public array $candidates = [];
    /** @var array<int,string> */
    public array $eliminated = [];

    // La validation se fait via l'attribut Rule
    public string $newCandidateName = '';

    protected function initializeParticipants(): void
    {
    }

    // ----------------------------------------------------------------------
    // 💡 Méthodes de Modification (Source de Vérité)
    // ----------------------------------------------------------------------

    /**
     * Ajoute un candidat et dispatch les événements de notification et de mise à jour.
     */
    public function addCandidate(): void
    {

        $this->validate([
            'newCandidateName' => 'required|string|min:2|max:50',
        ], [
            'newCandidateName.required' => 'Le nom du participant est requis.',
            'newCandidateName.min' => 'Le nom doit contenir au moins 2 caractères.',
        ]);

        $name = trim($this->newCandidateName);
        if (in_array($name, $this->candidates, true) || in_array($name, $this->eliminated, true)) {
            $this->addError('newCandidateName', "Le candidat '{$name}' existe déjà dans la liste.");
            return;
        }

        $this->candidates[] = $name; // Pour forcer l'unicité même si le nom est le même;
        $this->newCandidateName = '';

        // 1. Notification (pas de session!)
        if ($this instanceof Component) {
            // 1. Notification
            $this->dispatch('show-notification', type: 'success', message: 'Participant ajouté !');

            // 2. Synchronisation
            $this->dispatch('participants-updated', candidates: $this->candidates);
        }

        $this->winnerAnnounced = false;
    }

    /**
     * Supprime un candidat et dispatch les événements.
     */
    public function removeCandidate(string $name): void
    {
        $this->candidates = array_values(array_filter($this->candidates, fn ($c) => $c !== $name));

        if ($this instanceof Component) {
            // 1. Notification
            $this->dispatch('show-notification', type: 'success', message: "Participant '{$name}' supprimé !");

            // 2. 🚨 Synchronisation des autres composants
            $this->dispatch('participants-updated', candidates: $this->candidates);
        }
        $this->winnerAnnounced = false;
    }

    /**
     * Renomme un candidat et dispatch les événements.
     */
    public function renameCandidate(string $oldName, string $newName): void
    {
        $new = trim($newName);
        $component = ($this instanceof Component) ? $this : null;
        $isError = false;

        // Validation basique
        if ($new === '' || mb_strlen($new) < 2 || mb_strlen($new) > 50) {
            if ($component) {
                $component->dispatch('show-notification', type: 'error', message: "Le nom doit contenir entre 2 et 50 caractères.");
            }
            return;
        }

        // Vérification de l'unicité
        if ($oldName !== $new && (in_array($new, $this->candidates, true) || in_array($new, $this->eliminated, true))) {
            if ($component) {
                $component->dispatch('show-notification', type: 'error', message: "Le candidat '{$new}' existe déjà.");
            }
            return;
        }

        // Trouver et remplacer le nom
        $found = false;
        foreach ($this->candidates as $i => $c) {
            if ($c === $oldName) {
                $this->candidates[$i] = $new;
                $found = true;
                break;
            }
        }

        if ($component) {
            if ($found) {
                $component->dispatch('show-notification', type: 'success', message: "Participant renommé en '{$new}'.");
                // 🚨 Synchronisation des autres composants (uniquement si réussi)
                $component->dispatch('participants-updated', candidates: $this->candidates);
            } else {
                $component->dispatch('show-notification', type: 'error', message: "Impossible de trouver le participant '{$oldName}'.");
            }
        }
    }

    /**
     * Réinitialise les listes et dispatch les événements.
     */
    public function resetGame(): void
    {
        $this->candidates = [];
        $this->eliminated = [];
        $this->newCandidateName = '';

        if ($this instanceof Component) {
            // 1. Notification
            $this->dispatch('show-notification', type: 'info', message: 'Le jeu a été réinitialisé.');

            // 2. 🚨 Synchronisation des autres composants
            $this->dispatch('participants-updated', candidates: $this->candidates);
        }
    }
}
