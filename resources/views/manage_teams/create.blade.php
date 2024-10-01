<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.create_team_title') }} | {{ $clubName }}</title>
    @if($logoPath)
        <link rel="icon" href="{{ $logoPath }}" type="image/png">
    @endif
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @vite('resources/css/app.css')

    <!-- Meta Tags for SEO -->
    <meta name="description" content="Create a new team for {{ $clubName }}. Add team name, logo, and manage the team effectively.">
    <meta name="keywords" content="create team, {{ $clubName }}, futsal, manage team">
    <meta property="og:title" content="Create Team - {{ $clubName }}">
    <meta property="og:description" content="Create and manage a new team in {{ $clubName }}. Add team details easily and effectively.">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">
</head>
<body class="bg-gray-100">

    <x-navbar />

    <header class="text-center my-12">
        <x-page-title subtitle="">
            {{ __('messages.create_team') }}
        </x-page-title>
    </header>

    <div class="container mx-auto mt-8 p-8 rounded-lg shadow-md border border-gray-300 max-w-3xl" style="margin-bottom: 50px;">

        @if ($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                <p class="font-bold">Please fix the following errors:</p>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('manage_teams.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Team Name -->
            <div class="mb-6">
                <label for="name" class="block text-lg font-medium text-gray-700 mb-2">{{ __('messages.team_name') }}:</label>
                <input type="text" name="name" id="name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 p-3" value="{{ old('name') }}" required>
                @error('name')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Team Logo -->
            <div class="mb-6">
                <label for="logo" class="block text-lg font-medium text-gray-700 mb-2">{{ __('messages.team_logo') }}:</label>
                <input type="file" name="logo" id="logo" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 p-3">
                @error('logo')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Form Buttons -->
            <div class="flex justify-center mt-8 space-x-4">
                <button type="submit" class="text-white font-bold py-2 px-6 rounded-full shadow-lg transition duration-200"
                    style="
                        background-color: {{ $primaryColor }};
                        font-size: 15px;
                        transition: background-color 0.3s ease;
                    "
                    onmouseover="this.style.backgroundColor='{{ $secondaryColor }}'"
                    onmouseout="this.style.backgroundColor='{{ $primaryColor }}'">
                    {{ __('messages.create_team_button') }}
                </button>

                <a href="{{ route('calendar.show') }}" class="text-white font-bold py-2 px-6 rounded-full shadow-lg transition duration-200 text-center"
                    style="
                        background-color: #DC2626;
                        font-size: 15px;
                        transition: background-color 0.3s ease;
                    "
                    onmouseover="this.style.backgroundColor='#B91C1C'"
                    onmouseout="this.style.backgroundColor='#DC2626'">
                    {{ __('messages.cancel') }}
                </a>
            </div>
        </form>
    </div>

    <x-footer />
</body>
</html>
