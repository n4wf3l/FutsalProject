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
</head>
<body class="bg-gray-100">
    <x-navbar />

    <div class="container mx-auto py-12">
        <div class="max-w-md mx-auto bg-white p-8 shadow-lg rounded-lg">
            <x-page-title subtitle="">
                {{ __('messages.create_team') }}
            </x-page-title>

            <!-- Display Validation Errors -->
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('manage_teams.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Team Name -->
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">{{ __('messages.team_name') }}</label>
                    <input type="text" name="name" id="name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ old('name') }}" required>
                    @error('name')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Team Logo -->
                <div class="mb-4">
                    <label for="logo" class="block text-sm font-medium text-gray-700">{{ __('messages.team_logo') }}</label>
                    <input type="file" name="logo" id="logo" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    @error('logo')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Form Buttons -->
                <div class="flex items-center justify-between">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                        {{ __('messages.create_team_button') }}
                    </button>
                    <a href="{{ route('calendar.show') }}" class="text-blue-500 hover:underline">
                        {{ __('messages.cancel') }}
                    </a>
                </div>
            </form>
        </div>
    </div>

    <x-footer />
</body>
</html>
