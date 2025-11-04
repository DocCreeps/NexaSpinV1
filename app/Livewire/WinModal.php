<?php

namespace App\Livewire;

use Livewire\Component;

class WinModal extends Component
{
    public ?string $winner = null;
    public bool $isOpen = false;

    protected $listeners = [
        'show-winner' => 'open',
    ];

    public function open(string $winner)
    {
        $this->winner = $winner;
        $this->isOpen = true;
    }

    public function close()
    {
        $this->isOpen = false;
        $this->winner = null;
    }

    public function render()
    {
        return view('livewire.win-modal');
    }
}

