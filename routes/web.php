<?php

// routes/web.php

use App\Livewire\ClassicRouletteComponent;
use App\Livewire\RussianRouletteComponent;
use Illuminate\Support\Facades\Route;

// 1. La nouvelle route de sélection
Route::view('/', 'roulette-selector')->name('roulette.selector');

Route::get('/roulette/classic', ClassicRouletteComponent::class);
Route::get('/roulette/russian', RussianRouletteComponent::class);

