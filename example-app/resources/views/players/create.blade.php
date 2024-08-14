<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Player</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100">
<div class="container mx-auto mt-8">
    <h1 class="text-3xl font-bold mb-4">Add a New Player</h1>

    <form action="{{ route('players.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <!-- Form fields for player details -->
        <div class="mb-4">
            <label for="first_name" class="block text-sm font-medium text-gray-700">First Name:</label>
            <input type="text" name="first_name" id="first_name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
        </div>
        <div class="mb-4">
            <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name:</label>
            <input type="text" name="last_name" id="last_name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
        </div>
        <div class="mb-4">
            <label for="photo" class="block text-sm font-medium text-gray-700">Photo:</label>
            <input type="file" name="photo" id="photo" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
        </div>
        <div class="mb-4">
            <label for="birthdate" class="block text-sm font-medium text-gray-700">Date of Birth:</label>
            <input type="date" name="birthdate" id="birthdate" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
        </div>
        <div class="mb-4">
            <label for="position" class="block text-sm font-medium text-gray-700">Position:</label>
            <input type="text" name="position" id="position" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
        </div>
        <div class="mb-4">
            <label for="number" class="block text-sm font-medium text-gray-700">Number:</label>
            <input type="number" name="number" id="number" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
        </div>
        <div class="mb-4">
            <label for="nationality" class="block text-sm font-medium text-gray-700">Nationality:</label>
            <input type="text" name="nationality" id="nationality" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
        </div>
        <div class="mb-4">
            <label for="height" class="block text-sm font-medium text-gray-700">Height (cm):</label>
            <input type="number" name="height" id="height" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
        </div>
        <div class="mb-4">
            <label for="contract_until" class="block text-sm font-medium text-gray-700">Contract Until:</label>
            <input type="date" name="contract_until" id="contract_until" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
        </div>

        <button type="submit" class="bg-blue-500 text-white font-bold py-2 px-4 rounded mt-4">
            Add Player
        </button>
    </form>
</div>
<x-footer />
</body>
</html>
