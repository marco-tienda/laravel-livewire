<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Livewire</title>

        <livewire:styles />

        <style>
            label {
                display: block;
            }
        </style>
    </head>
    <body class="antialiased">

        @if (session('status'))
            <div>{{ session('status') }}</div>
        @endif

        {{ $slot }}

        <livewire:scripts />
    </body>
</html>
