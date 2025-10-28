<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />


    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <script>
        window.addEventListener('spin-start', (e) => {
            const { planned, candidates, duration } = e.detail || {};
            const wheel = document.getElementById('wheel');
            if (!wheel || !Array.isArray(candidates) || !planned) return;

            const idx = candidates.indexOf(planned);
            if (idx < 0) return;

            const slice = 360 / candidates.length;
            const targetAngle = (idx + 0.5) * slice;
            const spins = 4;
            const finalRotation = -(spins * 360 + targetAngle);

            wheel.style.transition = 'none';
            wheel.style.transform = 'rotate(0deg)';

            requestAnimationFrame(() => {
                wheel.style.transition = `transform ${Math.max(0.5, duration / 1000)}s cubic-bezier(0.25, 0.8, 0.25, 1)`;
                wheel.style.transform = `rotate(${finalRotation}deg)`;
            });
        });
    </script>


</head>
<body class="bg-white text-gray-900 flex p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col">

<div class="w-full max-w-3xl" style="max-width: 1000px;">
    @isset($slot)
        {{ $slot }}
    @else
        @yield('content')
    @endisset

</div>
@livewireScripts

</body>
</html>
