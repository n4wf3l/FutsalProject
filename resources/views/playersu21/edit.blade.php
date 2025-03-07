<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.edit_u21_player_title') }} | {{ $clubName }}</title>
    @if($logoPath)
        <link rel="icon" href="{{ $logoPath }}" type="image/png"> <!-- Adjusted for dynamic image type -->
    @endif
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100">

    <x-navbar />

    <header class="text-center my-12">
        <x-page-title subtitle="">
            {{ __('messages.edit_u21_player_title') }}
        </x-page-title>
    </header>

    <div class="container mx-auto mt-8 p-8 rounded-lg shadow-md border border-gray-300 max-w-3xl" style="margin-bottom: 50px;">

        <form action="{{ route('playersu21.update', $playerU21->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- First Name -->
            <div class="mb-4">
                <label for="first_name" class="block text-sm font-medium text-gray-700">{{ __('messages.first_name') }}:</label>
                <input type="text" name="first_name" id="first_name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ old('first_name', $playerU21->first_name) }}" required>
            </div>

            <!-- Last Name -->
            <div class="mb-4">
                <label for="last_name" class="block text-sm font-medium text-gray-700">{{ __('messages.last_name') }}:</label>
                <input type="text" name="last_name" id="last_name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ old('last_name', $playerU21->last_name) }}" required>
            </div>

            <!-- Photo -->
            <div class="mb-4">
                <label for="photo" class="block text-sm font-medium text-gray-700">{{ __('messages.photo') }}:</label>
                <input type="file" name="photo" id="photo" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                @if($playerU21->photo)
                    <img src="{{ asset('storage/' . $playerU21->photo) }}" alt="Player Photo" class="mt-2 mx-auto" style="width: 150px;">
                @endif
            </div>

            <!-- Date of Birth -->
            <div class="mb-4">
                <label for="birthdate" class="block text-sm font-medium text-gray-700">{{ __('messages.birthdate') }}:</label>
                <input type="date" name="birthdate" id="birthdate" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ old('birthdate', $playerU21->birthdate) }}" required>
            </div>

            <!-- Position -->
            <div class="mb-4">
                <label for="position" class="block text-sm font-medium text-gray-700">{{ __('messages.position') }}:</label>
                <input type="text" name="position" id="position" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ old('position', $playerU21->position) }}" required>
            </div>

            <!-- Number -->
            <div class="mb-4">
                <label for="number" class="block text-sm font-medium text-gray-700">{{ __('messages.number') }}:</label>
                <input type="number" name="number" id="number" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ old('number', $playerU21->number) }}" required>
            </div>

            <!-- Nationality -->
            <div class="mb-4">
                <label for="nationality" class="block text-sm font-medium text-gray-700">{{ __('messages.nationality') }}:</label>
                <input type="text" name="nationality" id="nationality" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ old('nationality', $playerU21->nationality) }}" required>
            </div>

            <!-- Height -->
            <div class="mb-4">
                <label for="height" class="block text-sm font-medium text-gray-700">{{ __('messages.height') }} (cm):</label>
                <input type="number" name="height" id="height" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ old('height', $playerU21->height) }}" required>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-center">
                <button type="submit" class="text-white font-bold py-2 px-6 rounded-full shadow-lg transition duration-200"
                    style="
                        background-color: {{ $userSettings->theme_color_primary ?? '#1D4ED8' }};
                        margin-bottom: 20px; 
                        font-size: 15px;
                        transition: background-color 0.3s ease;
                    "
                    onmouseover="this.style.backgroundColor='{{ $userSettings->theme_color_secondary ?? '#E53E3E' }}'"
                    onmouseout="this.style.backgroundColor='{{ $userSettings->theme_color_primary ?? '#1D4ED8' }}'">
                    {{ __('messages.update_player_button') }}
                </button>
            </div>
        </form>
    </div>

    <x-footer />
</body>
</html>
