<?php

use App\Livewire\ClassicRouletteComponent;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(ClassicRouletteComponent::class)
        ->assertStatus(200);
});
