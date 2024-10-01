<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.edit_tribune_title') }} | {{ $clubName }}</title>
    @if($logoPath)
        <link rel="icon" href="{{ $logoPath }}" type="image/png">
    @endif
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @vite('resources/css/app.css')

    <!-- Meta Tags for SEO -->
    <meta name="description" content="Edit the tribune details of {{ $clubName }}. Modify tribune name, description, and available seats.">
    <meta name="keywords" content="edit tribune, {{ $clubName }}, futsal, tribune details">
    <meta property="og:title" content="Edit Tribune - {{ $clubName }}">
    <meta property="og:description" content="Edit the details of an existing tribune in {{ $clubName }} and manage tribune information.">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">
</head>
<body class="bg-gray-100">

    <x-navbar />

    <header class="text-center my-12">
        <x-page-title subtitle="">
            {{ __('messages.edit_tribune_title') }}
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

        <form action="{{ route('tribunes.update', $tribune->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Tribune Name -->
            <div class="mb-6">
                <label for="name" class="block text-lg font-medium text-gray-700 mb-2">{{ __('messages.tribune_name') }}:</label>
                <input type="text" name="name" id="name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 p-3" value="{{ old('name', $tribune->name) }}" required>
            </div>

            <!-- Tribune Description -->
            <div class="mb-6">
                <label for="description" class="block text-lg font-medium text-gray-700 mb-2">{{ __('messages.tribune_description') }}:</label>
                <textarea name="description" id="description" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 p-3">{{ old('description', $tribune->description) }}</textarea>
            </div>

            <!-- Price and Currency -->
            <div class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="price" class="block text-lg font-medium text-gray-700 mb-2">{{ __('messages.price') }}:</label>
                    <input type="number" name="price" id="price" step="0.01" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 p-3 text-xl font-bold text-center" value="{{ old('price', $tribune->price) }}" required>
                </div>

                <div>
                    <label for="currency" class="block text-lg font-medium text-gray-700 mb-2">{{ __('messages.currency') }}:</label>
                    <select name="currency" id="currency" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 p-3 text-xl font-bold text-center">
                        <option value="€" {{ old('currency', $tribune->currency) == '€' ? 'selected' : '' }}>€</option>
                        <option value="DH" {{ old('currency', $tribune->currency) == 'DH' ? 'selected' : '' }}>DH</option>
                        <option value="$" {{ old('currency', $tribune->currency) == '$' ? 'selected' : '' }}>$</option>
                    </select>
                </div>
            </div>

            <!-- Available Seats -->
            <div class="mb-6">
                <label for="available_seats" class="block text-lg font-medium text-gray-700 mb-2">{{ __('messages.available_seats') }}:</label>
                <input type="number" name="available_seats" id="available_seats" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 p-3" min="0" value="{{ old('available_seats', $tribune->available_seats) }}" required>
            </div>

            <!-- Tribune Photo -->
            <div class="mb-6">
                <label for="photo" class="block text-lg font-medium text-gray-700 mb-2">{{ __('messages.tribune_photo') }}:</label>
                <input type="file" name="photo" id="photo" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 p-3">
                
                <!-- Show current photo if exists -->
                @if($tribune->photo)
                    <img src="{{ asset('storage/' . $tribune->photo) }}" alt="{{ __('messages.tribune_photo') }}" class="mt-4 max-h-40 rounded-md shadow-lg">
                @endif
            </div>

            <!-- Save Button -->
            <div class="flex justify-center mt-8">
                <button type="submit" class="text-white font-bold py-2 px-6 rounded-full shadow-lg transition duration-200"
                    style="
                        background-color: {{ $primaryColor }};
                        margin-bottom: 20px; 
                        font-size: 15px;
                        transition: background-color 0.3s ease;
                    "
                    onmouseover="this.style.backgroundColor='{{ $secondaryColor }}'"
                    onmouseout="this.style.backgroundColor='{{ $primaryColor }}'">
                    {{ __('messages.save_changes_button') }}
                </button>
            </div>
        </form>
    </div>

    <x-footer />
</body>
</html>
