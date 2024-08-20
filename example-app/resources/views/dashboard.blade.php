<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | {{ $clubName }}</title>
    @if($logoPath)
        <link rel="icon" href="{{ $logoPath }}" type="image/png"> <!-- Type de l'image selon le type du logo -->
    @endif
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @vite('resources/css/app.css')
    <style>
        .button-hover-primary {
            background-color: {{ $userSettings->theme_color_primary ?? '#1D4ED8' }};
            color: white;
        }
        .button-hover-primary:hover {
            background-color: {{ $userSettings->theme_color_secondary ?? '#FFFFFF' }};
            color: black; /* Facultatif : Change la couleur du texte en noir lors du survol */
        }
    </style>
</head>
<body>
    <x-navbar />

    <header class="text-center my-12">
    <x-page-title subtitle="📊 Welcome to your dashboard, {{ Auth::user()->name }}!">
        Dashboard
    </x-page-title>

    <a href="{{ route('profile.edit') }}" class="ml-6 text-white font-bold py-2 px-6 rounded-full transition duration-200 shadow-lg text-center button-hover-primary" style="margin-left:5px; font-size:15px;">
                My Profile
            </a>
</header>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <section class="max-w-5xl mx-auto mb-20 mt-20">
        <!-- Boutons pour gérer les entités du site -->
        <div class="flex flex-wrap justify-center gap-6" style="margin-bottom:70px;">
            <a href="{{ route('players.create') }}" class="text-white font-bold py-2 px-6 rounded-full transition duration-200 shadow-lg text-center button-hover-primary">
                Add Player
            </a>
            <a href="{{ route('teams') }}" class="text-white font-bold py-2 px-6 rounded-full transition duration-200 shadow-lg text-center button-hover-primary">
                Edit Player
            </a>
            <a href="{{ route('staff.create') }}" class="text-white font-bold py-2 px-6 rounded-full transition duration-200 shadow-lg text-center button-hover-primary">
                Add Staff
            </a>
            <a href="{{ route('teams') }}" class="text-white font-bold py-2 px-6 rounded-full transition duration-200 shadow-lg text-center button-hover-primary">
                Edit Staff
            </a>
            <a href="{{ route('sponsors.create') }}" class="text-white font-bold py-2 px-6 rounded-full transition duration-200 shadow-lg text-center button-hover-primary">
                Add Sponsor
            </a>
            <a href="{{ route('articles.create') }}" class="text-white font-bold py-2 px-6 rounded-full transition duration-200 shadow-lg text-center button-hover-primary">
                Add News
            </a>
            <a href="{{ route('clubinfo') }}" class="text-white font-bold py-2 px-6 rounded-full transition duration-200 shadow-lg text-center button-hover-primary">
                Edit News
            </a>
            <a href="{{ route('coaches.create') }}" class="text-white font-bold py-2 px-6 rounded-full transition duration-200 shadow-lg text-center button-hover-primary">
                Add Coach
            </a>
        </div>

        <!-- Conteneur pour les personnalisations du site -->
        <div class="flex flex-wrap justify-center space-x-20 mt-28" style="width:100%;">
    <!-- Conteneur pour les paramètres utilisateur -->
    <div class="p-8 rounded-lg shadow-md bg-white max-w-md"style="width:50%;">
        <form action="{{ route('user.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            @method('PUT')

            <div class="mb-6 flex flex-col items-center">
                <label for="theme_color_primary" class="block text-sm font-medium text-gray-700 mb-2">Primary Theme Color:</label>
                <input type="color" name="theme_color_primary" id="theme_color_primary" class="block max-w-xs border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" value="{{ old('theme_color_primary', $userSettings->theme_color_primary ?? '#000000') }}">
            </div>

            <div class="mb-6 flex flex-col items-center">
                <label for="theme_color_secondary" class="block text-sm font-medium text-gray-700 mb-2">Secondary Theme Color:</label>
                <input type="color" name="theme_color_secondary" id="theme_color_secondary" class="block max-w-xs border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" value="{{ old('theme_color_secondary', $userSettings->theme_color_secondary ?? '#FFFFFF') }}">
            </div>

            <div class="mb-6 flex flex-col items-center">
                <label for="club_name" class="block text-sm font-medium text-gray-700 mb-2">Nom de Club:</label>
                <input type="text" name="club_name" id="club_name" class="block max-w-md border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" value="{{ old('club_name', $userSettings->club_name ?? '') }}">
            </div>

            <div class="mb-6 flex flex-col items-center">
                <label for="logo" class="block text-sm font-medium text-gray-700 mb-2">Logo:</label>
                <input type="file" name="logo" id="logo" class="block max-w-md border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                @if($userSettings && $userSettings->logo)
                    <img src="{{ asset('storage/' . $userSettings->logo) }}" alt="Logo" class="mt-4" style="max-height: 80px; width: auto;">
                @endif
            </div>

            <div class="flex justify-center">
                <button type="submit" style="background-color: {{ $userSettings->theme_color_primary ?? '#1D4ED8' }}; margin-bottom:20px;" class="text-white font-bold py-2 px-6 rounded-full hover:opacity-80 transition duration-200 shadow-lg text-center">
                    Save Settings
                </button>
            </div>
        </form>
    </div>

    <!-- Conteneur pour les informations du club -->
    <div class="p-8 rounded-lg shadow-md bg-white max-w-md" style="width:70%;">
        <form action="{{ route('club-info.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf

            <div class="mb-6 flex flex-col items-center">
                <label for="sportcomplex_location" class="block text-sm font-medium text-gray-700 mb-2">Lieu du Sportcomplex:</label>
                <input type="text" name="sportcomplex_location" id="sportcomplex_location" class="block max-w-md border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" value="{{ old('sportcomplex_location', $clubInfo->sportcomplex_location ?? '') }}">
            </div>

            <div class="mb-6 flex flex-col items-center">
        <label for="city" class="block text-sm font-medium text-gray-700 mb-2">Ville:</label>
        <input type="text" name="city" id="city" class="block max-w-md border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" value="{{ old('city', $clubInfo->city ?? '') }}">
    </div>

            <div class="mb-6 flex flex-col items-center">
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Numéro de téléphone:</label>
                <input type="text" name="phone" id="phone" class="block max-w-md border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" value="{{ old('phone', $clubInfo->phone ?? '') }}">
            </div>

            <div class="mb-6 flex flex-col items-center">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email:</label>
                <input type="email" name="email" id="email" class="block max-w-md border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" value="{{ old('email', $clubInfo->email ?? '') }}">
            </div>

            <div class="mb-6 flex flex-col items-center">
                <label for="federation_logo" class="block text-sm font-medium text-gray-700 mb-2">Logo de la Fédération:</label>
                <input type="file" name="federation_logo" id="federation_logo" class="block max-w-md border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                @if($clubInfo && $clubInfo->federation_logo)
                    <img src="{{ asset('storage/' . $clubInfo->federation_logo) }}" alt="Logo" class="mt-4" style="max-height: 80px; width: auto;">
                @endif
            </div>

            <div class="mb-6 flex flex-col items-center">
                <label for="facebook" class="block text-sm font-medium text-gray-700 mb-2">Facebook:</label>
                <input type="text" name="facebook" id="facebook" class="block max-w-md border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" value="{{ old('facebook', $clubInfo->facebook ?? '') }}">
            </div>

            <div class="mb-6 flex flex-col items-center">
                <label for="instagram" class="block text-sm font-medium text-gray-700 mb-2">Instagram:</label>
                <input type="text" name="instagram" id="instagram" class="block max-w-md border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" value="{{ old('instagram', $clubInfo->instagram ?? '') }}">
            </div>

            <div class="mb-6 flex flex-col items-center">
                <label for="president" class="block text-sm font-medium text-gray-700 mb-2">Président:</label>
                <input type="text" name="president" id="president" class="block max-w-md border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" value="{{ old('president', $clubInfo->president ?? '') }}">
            </div>

            <div class="mb-6 flex flex-col items-center">
                <label for="latitude" class="block text-sm font-medium text-gray-700 mb-2">Latitude:</label>
                <input type="text" name="latitude" id="latitude" class="block max-w-md border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" value="{{ old('latitude', $clubInfo->latitude ?? '') }}">
            </div>

            <div class="mb-6 flex flex-col items-center">
                <label for="longitude" class="block text-sm font-medium text-gray-700 mb-2">Longitude:</label>
                <input type="text" name="longitude" id="longitude" class="block max-w-md border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" value="{{ old('longitude', $clubInfo->longitude ?? '') }}">
            </div>

            <div class="flex justify-center">
                <button type="submit" style="background-color: {{ $userSettings->theme_color_primary ?? '#1D4ED8' }}; margin-bottom:20px;" class="text-white font-bold py-2 px-6 rounded-full hover:opacity-80 transition duration-200 shadow-lg text-center">
                    Save Club Info
                </button>
            </div>
        </form>
    </div>
</div>

<div class="flex flex-wrap justify-center mt-28 w-full">
    <!-- Formulaire pour ajouter des images de fond -->
    <div class="p-8 rounded-lg shadow-md bg-white max-w-md w-full md:w-1/2 lg:w-1/3">
        <form action="{{ route('dashboard.storeBackgroundImage') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf

            <div class="mb-6 flex flex-col items-center">
                <label for="background_image" class="block text-sm font-medium text-gray-700 mb-2">Ajouter une image de fond:</label>
                <input type="file" name="background_image" id="background_image" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div class="flex justify-center">
                <button type="submit" style="background-color: {{ $userSettings->theme_color_primary ?? '#1D4ED8' }}; margin-bottom:20px;" class="text-white font-bold py-2 px-6 rounded-full hover:opacity-80 transition duration-200 shadow-lg text-center">
                    Ajouter
                </button>
            </div>
        </form>
    </div>

    <!-- Liste des images -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
    @foreach($backgroundImages as $image)
    <div class="relative">
        <img src="{{ asset('storage/' . $image->image_path) }}" alt="Background" class="w-full h-48 object-cover rounded-lg">
        
        <form action="{{ route('dashboard.assignBackground') }}" method="POST" class="mt-2">
            @csrf
            <div class="flex justify-between items-center">
                <select name="page" class="border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="" @if(is_null($image->assigned_page)) selected @endif>Aucune page</option>
                    <option value="welcome" @if($image->assigned_page == 'welcome') selected @endif>Welcome Page</option>
                    <option value="calendar" @if($image->assigned_page == 'calendar') selected @endif>Calendar Page</option>
                    <option value="about" @if($image->assigned_page == 'about') selected @endif>About Page</option>
                    <!-- Ajoutez d'autres pages ici -->
                </select>
                <input type="hidden" name="image_id" value="{{ $image->id }}">
                <button type="submit" class="ml-2 text-white font-bold py-1 px-4 rounded-full transition duration-200 shadow-lg text-center" style="background-color: {{ $userSettings->theme_color_primary ?? '#1D4ED8' }};">
                    Apply
                </button>
            </div>
        </form>

        <form action="{{ route('dashboard.deleteBackgroundImage', $image->id) }}" method="POST" class="mt-2" onsubmit="return confirm('Are you sure you want to delete this image?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="absolute top-2 right-2 text-red-600 bg-white rounded-full p-1 shadow hover:bg-red-100">
                &times;
            </button>
        </form>
    </div>
    @endforeach
</div>
    </section>

    <x-footer />
</body>
</html>
