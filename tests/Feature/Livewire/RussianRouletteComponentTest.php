<?php

use App\Livewire\EliminationRouletteComponent;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(EliminationRouletteComponent::class)
        ->assertStatus(200);
});
