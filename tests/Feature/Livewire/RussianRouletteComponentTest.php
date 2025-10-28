<?php

use App\Livewire\RussianRouletteComponent;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(RussianRouletteComponent::class)
        ->assertStatus(200);
});
