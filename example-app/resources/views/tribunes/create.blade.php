@php
    // Récupère l'image de la première tribune existante, si disponible
    $existingPhoto = App\Models\Tribune::whereNotNull('photo')->first()->photo ?? null;
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Tribune | {{ $clubName }}</title>
    @if($logoPath)
        <link rel="icon" href="{{ $logoPath }}" type="image/png">
    @endif
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100">
    <x-navbar />

    <div class="container mx-auto py-12">
        <div class="bg-white rounded-lg shadow-lg p-8 max-w-3xl mx-auto">
            <h1 class="text-4xl font-bold text-center mb-8" style="color: {{ $primaryColor }};">Add Tribune</h1>
            <form action="{{ route('tribunes.store') }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow-md">
    @csrf

    <!-- Tribune Name -->
    <div class="mb-6">
        <label for="name" class="block text-lg font-medium text-gray-700 mb-2">Tribune Name:</label>
        <input type="text" name="name" id="name" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-{{ $primaryColor }} focus:border-{{ $primaryColor }} p-3" required>
    </div>

    <!-- Tribune Description -->
    <div class="mb-6">
        <label for="description" class="block text-lg font-medium text-gray-700 mb-2">Tribune Description:</label>
        <textarea name="description" id="description" rows="4" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-{{ $primaryColor }} focus:border-{{ $primaryColor }} p-3"></textarea>
    </div>

    <!-- Price and Currency -->
    <div class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label for="price" class="block text-lg font-medium text-gray-700 mb-2">Price:</label>
            <input type="number" step="0.01" name="price" id="price" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-{{ $primaryColor }} focus:border-{{ $primaryColor }} p-3 text-xl font-bold text-center" required>
        </div>
        <div>
            <label for="currency" class="block text-lg font-medium text-gray-700 mb-2">Currency:</label>
            <select name="currency" id="currency" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-{{ $primaryColor }} focus:border-{{ $primaryColor }} p-3 text-xl font-bold text-center">
                <option value="€" selected>€</option>
                <option value="DH">DH</option>
                <option value="$">$</option>
            </select>
        </div>
    </div>

    <!-- Available Seats -->
    <div class="mb-6">
        <label for="available_seats" class="block text-lg font-medium text-gray-700 mb-2">Available Seats:</label>
        <input type="number" name="available_seats" id="available_seats" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-{{ $primaryColor }} focus:border-{{ $primaryColor }} p-3" min="0" required>
    </div>

    <!-- Tribune Photo -->
    <div class="mb-6">
        <label for="photo" class="block text-lg font-medium text-gray-700 mb-2">Tribune Photo:</label>
        <input type="file" name="photo" id="photo" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-{{ $primaryColor }} focus:border-{{ $primaryColor }} p-3">
        @if($existingPhoto)
            <img src="{{ asset('storage/' . $existingPhoto) }}" alt="Tribune Image" class="mt-4 max-h-40 rounded-md shadow-lg">
        @endif
    </div>

    <!-- Save Button -->
    <div class="flex justify-center mt-8">
        <button type="submit" class="bg-{{ $primaryColor }} text-white font-bold py-3 px-8 rounded-full hover:bg-{{ $secondaryColor }} transition duration-200 shadow-lg">
            Save Tribune
        </button>
    </div>
</form>
        </div>
    </div>

    <x-footer />
</body>
</html>
