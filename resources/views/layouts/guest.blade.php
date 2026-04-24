<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'FitLife') }}</title>
        <style>html,body{overscroll-behavior-y:none;overscroll-behavior-x:auto}</style>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased font-sans bg-zinc-950 text-zinc-100">
        <div class="guest-container">
            <div class="guest-logo">
                <a href="/">FitLife</a>
            </div>
            <div class="guest-card">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
