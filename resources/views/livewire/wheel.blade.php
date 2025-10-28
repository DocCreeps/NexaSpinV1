@php
    $count = count($candidates);
    $textDistance = 30;
    // Génère le CSS du conic-gradient dans une variable pour la clarté
    $conicGradient = collect($candidates)->map(function ($name, $i) use ($count) {
        return "hsl(" . ($i * 360 / max($count, 1)) . ", 80%, 70%) " . (100 * $i / $count) . "% " . (100 * ($i + 1) / $count) . "%";
    })->implode(', ');
@endphp

<div x-data="{ rotation: 0 }"
     x-on:spin-start.window="
        const { planned, candidates, duration } = $event.detail;
        if (!planned || !candidates.length) return;

        // ... [LOGIQUE DE CALCUL DE LA ROTATION INCHANGÉE] ...
        const index = candidates.indexOf(planned);
        if (index === -1) return;
        const degPerSlice = 360 / candidates.length;
        const fullRotations = 6;
        const segmentCenterAngle = index * degPerSlice + degPerSlice / 2;
        const rotationTo0Deg = 360 - segmentCenterAngle;
        const pointerOffset = 0;
        const targetDeg = rotationTo0Deg - pointerOffset + fullRotations * 360;

        rotation = targetDeg;

        const inner = $el.querySelector('.wheel-inner');

        // 🟢 Application de la transition au début du spin
        inner.style.transition = `transform ${duration}ms cubic-bezier(0.33, 1, 0.68, 1)`;
        inner.style.transform = `rotate(${targetDeg}deg)`;

        setTimeout(() => {
            // 🟢 Réinitialisation de la transition après le spin
            inner.style.transition = 'none';
            let finalDeg = targetDeg % 360;
            if (finalDeg < 0) {
                 finalDeg += 360;
            }
            inner.style.transform = `rotate(${finalDeg}deg)`;
            $wire.dispatch('roulette-finished');
        }, duration);
     "
     class="relative w-64 h-64 md:w-80 md:h-80 mx-auto">

    {{-- Pointeur (Flèche) --}}
    <div class="absolute top-0 left-1/2 z-10 -translate-x-1/2 -translate-y-1/2">
        <div class="w-0 h-0 border-l-8 border-r-8 border-t-10 border-l-transparent border-r-transparent border-b-red-600"></div>
    </div>

    {{-- Roue (Partie tournante) --}}
    <div wire:ignore.self  {{-- 🛑 FIX : wire:ignore.self préserve les attributs (style/transition) mais met à jour le contenu --}}
    class="wheel-inner w-full h-full rounded-full border-8 border-gray-200 select-none relative"
         style="transition: transform 0s;
                background: conic-gradient({{ $conicGradient }});"
    >

        {{-- Le contenu de cette boucle est maintenant mis à jour car wire:ignore.self est sur le parent, pas ici --}}
        @foreach($candidates as $i => $name)
            @php
                $degPerSlice = 360 / max($count, 1);
                $angle = $i * $degPerSlice + $degPerSlice / 2;
            @endphp

            <div class="absolute inset-0 flex items-center justify-center text-xs font-medium text-gray-700 pointer-events-none"
                 style="
                    transform:
                        rotate({{ $angle }}deg)
                        translateY(-{{ $textDistance }}%);
                 ">

                <span style="
                    transform:
                        rotate(-{{ $angle }}deg)
                        translateX(-50%);
                 ">
                    {{ $name }}
                </span>
            </div>
        @endforeach
    </div>
</div>
