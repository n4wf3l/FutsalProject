<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.edit_tribune_title') }} | {{ $clubName }}</title>
    @if($logoPath)
        <link rel="icon" href="{{ $logoPath }}" type="image/png">
    @endif
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100">
    <x-navbar />

    <div class="container mx-auto py-12 px-4">
        <div class="bg-white rounded-lg shadow-lg p-8 max-w-3xl mx-auto">
            <x-page-title subtitle="">
                {{ __('messages.edit_tribune_title') }}
            </x-page-title>
            <form action="{{ route('tribunes.update', $tribune->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Nom de la Tribune -->
                <div class="mb-6">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.tribune_name') }}:</label>
                    <input type="text" name="name" id="name" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" value="{{ old('name', $tribune->name) }}" required>
                </div>

                <!-- Description de la Tribune -->
                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.tribune_description') }}:</label>
                    <textarea name="description" id="description" rows="4" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">{{ old('description', $tribune->description) }}</textarea>
                </div>

                <!-- Prix de la Tribune et Devise -->
                <div class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.price') }}:</label>
                        <input type="number" name="price" id="price" step="0.01" class="w-full text-xl font-bold text-center border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 p-3" value="{{ old('price', $tribune->price) }}" required>
                    </div>

                    <div>
                        <label for="currency" class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.currency') }}:</label>
                        <select name="currency" id="currency" class="w-full text-xl font-bold text-center border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 p-3">
                            <option value="€" {{ old('currency', $tribune->currency) == '€' ? 'selected' : '' }}>€</option>
                            <option value="DH" {{ old('currency', $tribune->currency) == 'DH' ? 'selected' : '' }}>DH</option>
                            <option value="$" {{ old('currency', $tribune->currency) == '$' ? 'selected' : '' }}>$</option>
                        </select>
                    </div>
                </div>

                <!-- Nombre de places disponibles -->
                <div class="mb-6">
                    <label for="available_seats" class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.available_seats') }}:</label>
                    <input type="number" name="available_seats" id="available_seats" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" min="0" value="{{ old('available_seats', $tribune->available_seats) }}" required>
                </div>

                <!-- Photo de la Tribune -->
                <div class="mb-6">
                    <label for="photo" class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.tribune_photo') }}:</label>
                    <input type="file" name="photo" id="photo" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    
                    <!-- Affiche l'image actuelle s'il y en a une -->
                    @if($tribune->photo)
                        <img src="{{ asset('storage/' . $tribune->photo) }}" alt="{{ __('messages.tribune_photo') }}" class="mt-4 max-h-40 rounded-md shadow-lg">
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
                        {{ __('messages.save_changes_button') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <x-footer />
</body>
</html>
