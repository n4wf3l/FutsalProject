<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Player | {{ $clubName }}</title>
    @if($logoPath)
        <link rel="icon" href="{{ $logoPath }}" type="image/png"> <!-- Type de l'image selon le type du logo -->
    @endif
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @vite('resources/css/app.css')

    <!-- Meta Tags for SEO -->
    <meta name="description" content="Add a new player to {{ $clubName }}. Enter player details including first name, last name, position, and more to create a player profile.">
    <meta name="keywords" content="add player, {{ $clubName }}, football, futsal, player details">
    <meta property="og:title" content="Add Player - {{ $clubName }}">
    <meta property="og:description" content="Add a new player to the roster of {{ $clubName }} and manage the player details effectively.">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">
</head>
<body class="bg-gray-100">

    <x-navbar />

    <header class="text-center my-12">
        <x-page-title subtitle="">
            Add New Player
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

        <form action="{{ route('players.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Form fields for player details -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
                <!-- First Name -->
                <div>
                    <label for="first_name" class="block text-sm font-medium text-gray-700">First Name:</label>
                    <input type="text" name="first_name" id="first_name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                </div>

                <!-- Last Name -->
                <div>
                    <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name:</label>
                    <input type="text" name="last_name" id="last_name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                </div>

                <!-- Date of Birth -->
                <div>
                    <label for="birthdate" class="block text-sm font-medium text-gray-700">Date of Birth:</label>
                    <input type="date" name="birthdate" id="birthdate" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                </div>

                <!-- Position -->
                <div>
                    <label for="position" class="block text-sm font-medium text-gray-700">Position:</label>
                    <input type="text" name="position" id="position" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                </div>

                <!-- Number -->
                <div>
                    <label for="number" class="block text-sm font-medium text-gray-700">Number:</label>
                    <input type="number" name="number" id="number" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                </div>

                <!-- Nationality -->
                <div>
                    <label for="nationality" class="block text-sm font-medium text-gray-700">Nationality:</label>
                    <input type="text" name="nationality" id="nationality" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                </div>

                <!-- Height -->
                <div>
                    <label for="height" class="block text-sm font-medium text-gray-700">Height (cm):</label>
                    <input type="number" name="height" id="height" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                </div>

                <!-- Contract Until -->
                <div>
                    <label for="contract_until" class="block text-sm font-medium text-gray-700">Contract Until:</label>
                    <input type="date" name="contract_until" id="contract_until" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                </div>

                <!-- Photo -->
                <div class="col-span-1 sm:col-span-2">
                    <label for="photo" class="block text-sm font-medium text-gray-700">Photo:</label>
                    <input type="file" name="photo" id="photo" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            <div class="flex justify-center">
                <button type="submit" class="text-white font-bold py-2 px-6 rounded-full shadow-lg transition duration-200"
                    style="
                        background-color: {{ $primaryColor }};
                        margin-bottom: 20px; 
                        font-size: 15px;
                        transition: background-color 0.3s ease;
                    "
                    onmouseover="this.style.backgroundColor='{{ $secondaryColor }}'"
                    onmouseout="this.style.backgroundColor='{{ $primaryColor }}'">
                    Add Player
                </button>
            </div>
        </form>
    </div>

    <x-footer />
</body>
</html>
