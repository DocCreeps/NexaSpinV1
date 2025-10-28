@extends('layouts.app')
@section('content')
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8">
                <h1 class="text-4xl font-extrabold text-gray-900 mb-8 text-center">
                    Choisissez un Mode 🎲
                </h1>
                <p class="text-center text-gray-600 mb-10">
                    Sélectionnez comment se déroule chaque manche, puis ajoutez vos participants.
                </p>

                <div class="grid md:grid-cols-2 gap-8">

                    {{-- Mode 1: Roulette Classique --}}
                    <div class="bg-indigo-50 p-6 rounded-xl shadow-lg border border-indigo-200 hover:shadow-2xl transition duration-300">
                        <h2 class="text-2xl font-bold text-indigo-700 mb-3">Roulette Classique 🍀</h2>
                        <p class="text-gray-600 mb-4">
                            Tirage aléatoire du gagnant
                        </p>
                        <a href="{{ url('/roulette/classic') }}"
                           class="w-full inline-block text-center py-3 px-4 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition">
                            Jouer en Classique
                        </a>
                    </div>

                    {{-- Mode 2: Roulette Russe --}}
                    <div class="bg-red-50 p-6 rounded-xl shadow-lg border border-red-200 hover:shadow-2xl transition duration-300">
                        <h2 class="text-2xl font-bold text-red-700 mb-3">Roulette par élimination 💀</h2>
                        <p class="text-gray-600 mb-4">
                            Tirage aléatoire avec élimination à chaque manche, avec affichage de l'ordre d'élimination.
                        </p>
                        <a href="{{ url('/roulette/elimination') }}"
                           class="w-full inline-block text-center py-3 px-4 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition">
                            Jouer en élimination
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
