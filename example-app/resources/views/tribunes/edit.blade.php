<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Tribune | {{ $clubName }}</title>
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
        <x-page-title subtitle="">
    Edit Tribune
</x-page-title>
            <form action="{{ route('tribunes.update', $tribune->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Nom de la Tribune -->
                <div class="mb-6">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Tribune Name:</label>
                    <input type="text" name="name" id="name" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" value="{{ old('name', $tribune->name) }}" required>
                </div>

                <!-- Description de la Tribune -->
                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Tribune Description:</label>
                    <textarea name="description" id="description" rows="4" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">{{ old('description', $tribune->description) }}</textarea>
                </div>

                <!-- Prix de la Tribune et Devise -->
                <div class="mb-6 flex items-center justify-center space-x-4">
                    <div class="flex flex-col items-center">
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Price:</label>
                        <input type="number" name="price" id="price" step="0.01" class="w-full text-xl font-bold text-center border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 p-3" value="{{ old('price', $tribune->price) }}" required>
                    </div>

                    <div class="flex flex-col items-center">
                        <label for="currency" class="block text-sm font-medium text-gray-700 mb-2">Currency:</label>
                        <select name="currency" id="currency" class="w-full text-xl font-bold text-center border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 p-3">
                            <option value="€" {{ old('currency', $tribune->currency) == '€' ? 'selected' : '' }}>€</option>
                            <option value="DH" {{ old('currency', $tribune->currency) == 'DH' ? 'selected' : '' }}>DH</option>
                            <option value="$" {{ old('currency', $tribune->currency) == '$' ? 'selected' : '' }}>$</option>
                        </select>
                    </div>
                </div>

                <!-- Nombre de places disponibles -->
                <div class="mb-6">
                    <label for="available_seats" class="block text-sm font-medium text-gray-700 mb-2">Available Seats:</label>
                    <input type="number" name="available_seats" id="available_seats" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" min="0" value="{{ old('available_seats', $tribune->available_seats) }}" required>
                </div>

                <!-- Photo de la Tribune -->
                <div class="mb-6 flex flex-col items-center">
                    <label for="photo" class="block text-sm font-medium text-gray-700 mb-2">Tribune Photo:</label>
                    <input type="file" name="photo" id="photo" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    
                    <!-- Affiche l'image actuelle s'il y en a une -->
                    @if($tribune->photo)
                        <img src="{{ asset('storage/' . $tribune->photo) }}" alt="Image de la Tribune" class="mt-4" style="max-height: 150px; width: auto;">
                    @endif
                </div>

                <!-- Bouton avec couleurs du thème -->
                <div class="flex justify-center mt-8">
                    <button type="submit" 
        style="background-color: {{ $primaryColor }}; 
               color: white; 
               font-family: 'Bebas Neue', sans-serif;
               font-weight: bold; 
               padding: 5px 10px; 
               border: none; 
               border-radius: 50px; 
               cursor: pointer; 
               transition: background-color 0.3s ease, transform 0.2s ease; 
               box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);"
        onmouseover="this.style.backgroundColor='{{ $secondaryColor }}'; this.style.transform='scale(1.05)';"
        onmouseout="this.style.backgroundColor='{{ $primaryColor }}'; this.style.transform='scale(1)';">
        Save Changes
</button>
                </div>
            </form>
        </div>
    </div>

    <x-footer />
</body>
</html>
