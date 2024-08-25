<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Player | {{ $clubName }}</title>
    @if($logoPath)
        <link rel="icon" href="{{ $logoPath }}" type="image/png">
    @endif
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100">
    <x-navbar />

    <header class="text-center my-12" style="margin-top: 20px; font-size:60px;">
        <h1 class="text-6xl font-bold text-gray-900">Edit Player</h1>
        <div class="flex justify-center items-center mt-4">
            <p class="text-xl text-gray-600 mb-20" style="margin-bottom:50px">Discover additional information by hovering with your mouse.</p>
        </div>
    </header>

    <div class="container mx-auto mt-8 max-w-xl border border-gray-300 p-15 rounded-lg shadow-md max-w-3xl mx-auto" style="margin-bottom:50px">
    <form action="{{ route('playersu21.update', $playerU21->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <!-- Form fields for player details -->
    <div class="mb-4">
        <label for="first_name" class="block text-sm font-medium text-gray-700">First Name:</label>
        <input type="text" name="first_name" id="first_name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ old('first_name', $playerU21->first_name) }}" required>
    </div>
    <div class="mb-4">
        <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name:</label>
        <input type="text" name="last_name" id="last_name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ old('last_name', $playerU21->last_name) }}" required>
    </div>
    <div class="mb-4">
        <label for="photo" class="block text-sm font-medium text-gray-700">Photo:</label>
        <input type="file" name="photo" id="photo" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
        @if($playerU21->photo)
            <img src="{{ asset('storage/' . $playerU21->photo) }}" alt="Player Photo" class="mt-2 mx-auto" style="width: 150px;">
        @endif
    </div>
    <div class="mb-4">
        <label for="birthdate" class="block text-sm font-medium text-gray-700">Date of Birth:</label>
        <input type="date" name="birthdate" id="birthdate" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ old('birthdate', $playerU21->birthdate) }}" required>
    </div>
    <div class="mb-4">
        <label for="position" class="block text-sm font-medium text-gray-700">Position:</label>
        <input type="text" name="position" id="position" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ old('position', $playerU21->position) }}" required>
    </div>
    <div class="mb-4">
        <label for="number" class="block text-sm font-medium text-gray-700">Number:</label>
        <input type="number" name="number" id="number" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ old('number', $playerU21->number) }}" required>
    </div>
    <div class="mb-4">
        <label for="nationality" class="block text-sm font-medium text-gray-700">Nationality:</label>
        <input type="text" name="nationality" id="nationality" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ old('nationality', $playerU21->nationality) }}" required>
    </div>
    <div class="mb-4">
        <label for="height" class="block text-sm font-medium text-gray-700">Height (cm):</label>
        <input type="number" name="height" id="height" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ old('height', $playerU21->height) }}" required>
    </div>

    <div class="flex justify-center">
        <!-- Button -->
        <button type="submit" class="ml-6 text-white font-bold py-2 px-6 rounded-full transition duration-200 shadow-lg text-center"
                style="margin-bottom:20px; 
                       background-color: {{ $userSettings->theme_color_primary ?? '#1D4ED8' }};
                       margin-left:5px;
                       font-size:15px;
                       transition: background-color 0.3s ease;"
                onmouseover="this.style.backgroundColor='{{ $userSettings->theme_color_secondary ?? '#E53E3E' }}'"
                onmouseout="this.style.backgroundColor='{{ $userSettings->theme_color_primary ?? '#1D4ED8' }}'">
            Update Player
        </button>
    </div>
</form>
    </div>
    <x-footer />
</body>
</html>
