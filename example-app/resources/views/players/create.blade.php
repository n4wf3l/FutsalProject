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
</head>
<body class="bg-gray-100">
    <!-- Navbar component -->
    <x-navbar />

    <div class="container mx-auto mt-12 p-6 bg-white rounded-lg shadow-lg" style="margin-bottom:50px;">
        <h1 class="text-4xl font-bold text-center text-gray-900 mb-8" style="color: {{ $primaryColor }};">Add a New Player</h1>

        <form action="{{ route('players.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <!-- Form fields for player details -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="first_name" class="block text-sm font-medium text-gray-700">First Name:</label>
                    <input type="text" name="first_name" id="first_name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                </div>
                <div>
                    <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name:</label>
                    <input type="text" name="last_name" id="last_name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                </div>
                <div>
                    <label for="birthdate" class="block text-sm font-medium text-gray-700">Date of Birth:</label>
                    <input type="date" name="birthdate" id="birthdate" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                </div>
                <div>
                    <label for="position" class="block text-sm font-medium text-gray-700">Position:</label>
                    <input type="text" name="position" id="position" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                </div>
                <div>
                    <label for="number" class="block text-sm font-medium text-gray-700">Number:</label>
                    <input type="number" name="number" id="number" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                </div>
                <div>
                    <label for="nationality" class="block text-sm font-medium text-gray-700">Nationality:</label>
                    <input type="text" name="nationality" id="nationality" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                </div>
                <div>
                    <label for="height" class="block text-sm font-medium text-gray-700">Height (cm):</label>
                    <input type="number" name="height" id="height" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                </div>
                <div>
                    <label for="contract_until" class="block text-sm font-medium text-gray-700">Contract Until:</label>
                    <input type="date" name="contract_until" id="contract_until" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                </div>
                <div class="col-span-2">
                    <label for="photo" class="block text-sm font-medium text-gray-700">Photo:</label>
                    <input type="file" name="photo" id="photo" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            <div class="text-center">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-200"  style="font-size:20px; background-color: {{ $primaryColor }};"
                   onmouseover="this.style.backgroundColor='{{ $secondaryColor }}'"
                   onmouseout="this.style.backgroundColor='{{ $primaryColor }}'">
                    Add Player
                </button>
            </div>
        </form>
    </div>

    <!-- Footer component -->
    <x-footer />
</body>
</html>
