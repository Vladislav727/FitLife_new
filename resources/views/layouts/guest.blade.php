<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'FitLife') }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            .guest-container {
                min-height: 100vh;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                padding: 1.5rem;
                background: linear-gradient(135deg, #0a0a0a 0%, #141414 50%, #0a0a0a 100%);
            }

            .guest-card {
                width: 100%;
                max-width: 28rem;
                padding: 2rem;
                background: #1f1f1f;
                border: 1px solid #2a2a2a;
                border-radius: 1rem;
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            }

            .guest-logo {
                display: flex;
                justify-content: center;
                margin-bottom: 1.5rem;
            }

            .guest-logo a {
                color: #22c55e;
                font-size: 2rem;
                font-weight: 700;
                text-decoration: none;
            }

            .text-gray-600 {
                color: #a1a1aa !important;
            }

            .text-gray-400 {
                color: #a1a1aa !important;
            }
        </style>
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
