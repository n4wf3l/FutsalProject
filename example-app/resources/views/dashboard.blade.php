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

        /* Layout container styles */
        .layout-container {
            display: flex;
            align-items: flex-start;
            gap: 20px;
        }

        /* Sidebar styles */
        .sidebar {
            width: 200px;
            background-color: #f8f9fa;
            padding: 15px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .sidebar a {
            display: block;
            padding: 10px 15px;
            margin-bottom: 10px;
            text-decoration: none;
            color: #333;
            font-weight: bold;
            border-radius: 5px;
            transition: background-color 0.2s ease;
        }

        .sidebar a:hover {
            background-color: {{ $userSettings->theme_color_primary ?? '#1D4ED8' }};
            color: white;
        }

        /* Main content styles */
        .content {
            flex: 1; /* This makes the content area take up the remaining space */
            padding: 20px;
        }

        .card {
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .form-container {
            display: flex;
            justify-content: space-between;
            gap: 20px; /* Espace entre les deux formulaires */
        }

        .form-container .card {
            flex: 1;
        }

        /* New styles for the white section under the navbar */
        .header-section {
            background-color: white;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.1);
        }

        .header-section h1 {
            margin: 0;
            color: {{ $userSettings->theme_color_primary ?? '#1D4ED8' }};
            font-size: 2rem;
            font-weight: bold;
        }

        .header-section p {
            margin: 0;
            color: grey;
            font-size: 1rem;
        }

        .header-section img {
            height: 50px;
            width: 50px;
            object-fit: cover;
            border-radius: 50%;
        }

        .header-section .account-section {
            display: flex;
            align-items: center;
            gap: 15px;
        }
    </style>
</head>
<body>
    <x-navbar />

    <div class="header-section">
        <div>
            <x-page-title subtitle="📊 Welcome to your dashboard, {{ Auth::user()->name }}!">
                DASHBOARD
            </x-page-title>
        </div>
        <div class="account-section" style="display: inline-block; border-radius: 50%; transition: background-color 0.3s ease;">
            <a href="{{ route('profile.edit') }}">
                <img src="{{ asset('account.png') }}" alt="Account" style="border-radius: 50%;">
            </a>
        </div>
    </div>

    <div class="layout-container">
        <div class="sidebar">
            <a href="{{ route('players.create') }}" lass="text-white font-bold py-2 px-6 rounded-full transition duration-200 shadow-lg text-center button-hover-primary">
                Add Player
            </a>
            <a href="{{ route('coaches.create') }}" lass="text-white font-bold py-2 px-6 rounded-full transition duration-200 shadow-lg text-center button-hover-primary">
                Add Coach
            </a>
            <a href="{{ route('staff.create') }}" lass="text-white font-bold py-2 px-6 rounded-full transition duration-200 shadow-lg text-center button-hover-primary">
                Add Staff
            </a>
            <a href="{{ route('teams') }}" lass="text-white font-bold py-2 px-6 rounded-full transition duration-200 shadow-lg text-center button-hover-primary">
                Edit Staff
            </a>
            <a href="{{ route('sponsors.create') }}" lass="text-white font-bold py-2 px-6 rounded-full transition duration-200 shadow-lg text-center button-hover-primary">
                Add Sponsor
            </a>
            <a href="{{ route('about.index') }}" lass="text-white font-bold py-2 px-6 rounded-full transition duration-200 shadow-lg text-center button-hover-primary">
                Add AboutSection
            </a>
            <a href="{{ route('articles.create') }}" lass="text-white font-bold py-2 px-6 rounded-full transition duration-200 shadow-lg text-center button-hover-primary">
                Add News
            </a>
            <a href="{{ route('press_releases.index') }}" lass="text-white font-bold py-2 px-6 rounded-full transition duration-200 shadow-lg text-center button-hover-primary">
                Add Press Release
            </a>
        </div>

        <div class="content">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form container for displaying forms side by side -->
            <div class="form-container">
                <!-- Form for User Settings -->
                <div class="card">
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

                <!-- Form for Club Info -->
                <div class="card">
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

           <!-- Form for Background Images -->
<section class="mb-16">
    <div class="card">
        <h2 class="text-xl font-semibold text-center mb-6" style="color: {{ $userSettings->theme_color_primary ?? '#1D4ED8' }};">
            Ajouter une Image de Fond
        </h2>
        <form action="{{ route('dashboard.storeBackgroundImage') }}" method="POST" enctype="multipart/form-data" class="space-y-6" onsubmit="return validateForm()">
            @csrf
            <div class="mb-6">
                <label for="background_image" class="block text-sm font-medium text-gray-700 mb-2">Sélectionner une image :</label>
                <input type="file" name="background_image" id="background_image" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-{{ $userSettings->theme_color_primary ?? 'blue' }}-500 focus:border-{{ $userSettings->theme_color_primary ?? 'blue' }}-500">
            </div>
            <div class="flex justify-center">
                <button type="submit" style="background-color: {{ $userSettings->theme_color_primary ?? '#1D4ED8' }};" class="text-white font-bold py-2 px-6 rounded-full hover:opacity-90 transition duration-200 shadow-lg text-center">
                    Ajouter
                </button>
            </div>
        </form>
    </div>
</section>

<script>
    function validateForm() {
        const fileInput = document.getElementById('background_image');
        if (fileInput.files.length === 0) {
            alert('Veuillez sélectionner une image avant de soumettre.');
            return false; // Empêche la soumission du formulaire
        }
        return true; // Permet la soumission du formulaire si une image est sélectionnée
    }
</script>

            <!-- List of Background Images -->
            <section class="mb-16">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($backgroundImages as $image)
                        <div class="relative rounded-lg overflow-hidden shadow-lg">
                            <img src="{{ asset('storage/' . $image->image_path) }}" alt="Background" class="w-full h-48 object-cover">
                            <div class="absolute bottom-0 left-0 right-0 p-4 bg-gradient-to-t from-black to-transparent">
                                <form action="{{ route('dashboard.assignBackground') }}" method="POST" class="mt-2">
                                    @csrf
                                    <div class="flex justify-between items-center">
                                        <select name="page" class="border-gray-300 rounded-md shadow-sm focus:ring-{{ $userSettings->theme_color_primary ?? 'blue' }}-500 focus:border-{{ $userSettings->theme_color_primary ?? 'blue' }}-500 bg-white text-gray-800 py-1 px-2">
                                            <option value="" @if(is_null($image->assigned_page)) selected @endif>Aucune page</option>
                                            <option value="welcome" @if($image->assigned_page == 'welcome') selected @endif>Welcome Page</option>
                                            <option value="calendar" @if($image->assigned_page == 'calendar') selected @endif>Calendar Page</option>
                                            <option value="about" @if($image->assigned_page == 'about') selected @endif>About Page</option>
                                            <option value="clubinfo" @if($image->assigned_page == 'clubinfo') selected @endif>News Page</option>
                                            <option value="press_releases" @if($image->assigned_page == 'press_releases') selected @endif>Press Releases</option>
                                            <option value="team" @if($image->assigned_page == 'team') selected @endif>Senior Team</option>
                                            <option value="teamu21" @if($image->assigned_page == 'teamu21') selected @endif>U21 Team</option>
                                            <option value="sponsor" @if($image->assigned_page == 'sponsor') selected @endif>Sponsor</option>
                                            <option value="contact" @if($image->assigned_page == 'contact') selected @endif>Contact</option>
                                            <option value="fanshop" @if($image->assigned_page == 'fanshop') selected @endif>Fanshop Page</option>
                                            <!-- Ajoutez d'autres pages ici -->
                                        </select>
                                        <input type="hidden" name="image_id" value="{{ $image->id }}">
                                        <button type="submit" class="ml-2 text-white font-bold py-1 px-4 rounded-full transition duration-200 shadow-lg text-center" style="background-color: {{ $userSettings->theme_color_primary ?? '#1D4ED8' }};">
                                            Appliquer
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <form action="{{ route('dashboard.deleteBackgroundImage', $image->id) }}" method="POST" class="absolute top-2 right-2" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette image ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 bg-white rounded-full p-1 shadow hover:bg-red-100">
                                    &times;
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </section>

            <!-- Registration Settings -->
            <section>
                <div class="card">
                    <h3 class="text-lg font-medium text-gray-700 mb-4">Registration Settings</h3>
                    <form action="{{ route('dashboard.updateRegistrationStatus') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label for="registration_open" class="block text-sm font-medium text-gray-700">Open Registration</label>
                            <select name="registration_open" id="registration_open" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="1" {{ $registrationOpen == 'true' ? 'selected' : '' }}>Yes</option>
                                <option value="0" {{ $registrationOpen == 'false' ? 'selected' : '' }}>No</option>
                            </select>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="text-white font-bold py-2 px-4 rounded-full" style="background-color: {{ $userSettings->theme_color_primary ?? '#1D4ED8' }};">
                                Save
                            </button>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </div>

    <x-footer />
</body>
</html>