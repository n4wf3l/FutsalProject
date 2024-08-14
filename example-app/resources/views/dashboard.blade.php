<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
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

    <header class="text-center my-12" style="margin-top: 20px; font-size:60px;">
        <h1 class="text-6xl font-bold text-gray-900">MyADMIN</h1>
        <div class="flex justify-center items-center mt-4" style="margin-bottom: 50px;">
            <p class="text-xl text-gray-600">Welcome to your dashboard, {{ Auth::user()->name }}!</p>
            <a href="{{ route('profile.edit') }}" class="ml-6 text-white font-bold py-2 px-6 rounded-full transition duration-200 shadow-lg text-center button-hover-primary" style="margin-left:5px; font-size:15px;">
                My Profile
            </a>
        </div>
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

    </section>

    <x-footer />
</body>
</html>
