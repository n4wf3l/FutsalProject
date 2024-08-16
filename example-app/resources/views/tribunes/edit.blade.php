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
            <h1 class="text-4xl font-bold text-center mb-8" style="color: {{ $primaryColor }};">Edit Tribune</h1>
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
                    <button type="submit" class="bg-{{ $primaryColor }} text-white font-bold py-2 px-6 rounded-full hover:bg-{{ $secondaryColor }} transition duration-200 shadow-lg">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <x-footer />
</body>
</html>
