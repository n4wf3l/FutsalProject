@php
    $existingPhoto = App\Models\Tribune::whereNotNull('photo')->first()->photo ?? null;
@endphp

<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.add_tribune') }} | {{ $clubName }}</title>
    @if($logoPath)
        <link rel="icon" href="{{ $logoPath }}" type="image/png">
    @endif
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @vite('resources/css/app.css')

    <!-- Meta Tags for SEO -->
    <meta name="description" content="Add a new tribune to {{ $clubName }}. Fill in details such as tribune name, description, and available seats.">
    <meta name="keywords" content="add tribune, {{ $clubName }}, futsal, tribune details">
    <meta property="og:title" content="Add Tribune - {{ $clubName }}">
    <meta property="og:description" content="Add a new tribune to the roster of {{ $clubName }} and manage tribune details effectively.">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">
</head>
<body class="bg-gray-100">

    <x-navbar />

    <header class="text-center my-12">
        <x-page-title subtitle="">
            {{ __('messages.add_tribune') }}
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

        <form action="{{ route('tribunes.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Tribune Name -->
            <div class="mb-6">
                <label for="name" class="block text-lg font-medium text-gray-700 mb-2">{{ __('messages.tribune_name') }}:</label>
                <input type="text" name="name" id="name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 p-3" required>
            </div>

            <!-- Tribune Description -->
            <div class="mb-6">
                <label for="description" class="block text-lg font-medium text-gray-700 mb-2">{{ __('messages.tribune_description') }}:</label>
                <textarea name="description" id="description" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 p-3"></textarea>
            </div>

            <!-- Price and Currency -->
            <div class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="price" class="block text-lg font-medium text-gray-700 mb-2">{{ __('messages.price') }}:</label>
                    <input type="number" step="0.01" name="price" id="price" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 p-3" required>
                </div>
                <div>
                    <label for="currency" class="block text-lg font-medium text-gray-700 mb-2">{{ __('messages.currency') }}:</label>
                    <select name="currency" id="currency" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 p-3">
                        <option value="€" selected>€</option>
                        <option value="DH">DH</option>
                        <option value="$">$</option>
                    </select>
                </div>
            </div>

            <!-- Available Seats -->
            <div class="mb-6">
                <label for="available_seats" class="block text-lg font-medium text-gray-700 mb-2">{{ __('messages.available_seats') }}:</label>
                <input type="number" name="available_seats" id="available_seats" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 p-3" min="0" required>
            </div>

            <!-- Tribune Photo -->
            <div class="mb-6">
                <label for="photo" class="block text-lg font-medium text-gray-700 mb-2">{{ __('messages.tribune_photo') }}:</label>
                <input type="file" name="photo" id="photo" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 p-3">
                @if($existingPhoto)
                    <img src="{{ asset('storage/' . $existingPhoto) }}" alt="{{ __('messages.tribune_image') }}" class="mt-4 max-h-40 rounded-md shadow-lg">
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
                    {{ __('messages.save_tribune') }}
                </button>
            </div>
        </form>
    </div>

    <x-footer />
</body>
</html>
