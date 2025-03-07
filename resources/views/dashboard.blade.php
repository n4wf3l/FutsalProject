<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.dashboard') }} | {{ $clubName }}</title>
    @if($logoPath)
    <link rel="icon" href="{{ $logoPath }}" type="image/png">
    @endif
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #f3f4f6; /* Light gray background */
        }

        .sidebar {
            width: 250px;
        height: 100vh; /* Full height to cover the entire viewport */
        overflow-y: auto; /* Enable vertical scrolling */
        background-color: {{ $userSettings->theme_color_primary ?? '#1D4ED8' }};
        color: white;
        padding: 20px;
        position: fixed;
        transition: all 0.3s;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            padding: 15px;
            color: white;
            text-decoration: none;
            transition: background-color 1.0s ease, color 0.3s ease;
        }

        .sidebar a:hover {
            background-color: {{ $userSettings->theme_color_secondary ?? '#FF0000' }};
            color: white;
        }

        .sidebar a i {
            margin-right: 10px;
        }

        .dropdown-button {
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            padding: 15px;
            font-size: 1.25rem; /* 2xl */
            font-weight: bold;
            background: none;
            border: none;
            color: white;
            text-align: left;
            position: relative;
        }

        .dropdown-button::after {
            content: '\25BC'; /* Unicode for downward arrow */
            font-size: 0.8em;
            margin-left: 10px;
        }

        .dropdown-content {
            max-height: 0;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            background-color: {{ $userSettings->theme_color_primary ?? '#1D4ED8' }};
            margin-top: 5px;
            padding-left: 20px;
            padding-right: 20px;
            border-left: 2px solid {{ $userSettings->theme_color_primary ?? '#1D4ED8' }};
            border-right: 2px solid {{ $userSettings->theme_color_primary ?? '#1D4ED8' }};
            border-bottom: 2px solid {{ $userSettings->theme_color_primary ?? '#1D4ED8' }};
            border-radius: 0 0 8px 8px;
            transition: max-height 0.5s ease-in-out, padding 0.5s ease-in-out;
        }

        .dropdown-content a {
            color: white;
            padding: 10px;
            text-decoration: none;
            transition: background-color 0.3s ease, color 0.3s ease;
            font-weight: normal;
        }

        .dropdown-content a:hover {
            background-color: {{ $userSettings->theme_color_secondary ?? '#FF0000' }};
            color: white;
        }

        .dropdown.open .dropdown-content {
            max-height: 500px; /* Increase to allow smooth sliding */
            padding-bottom: 10px; /* Add padding for better appearance */
        }

        .main-content {
    margin-left: 250px;
    width: calc(100% - 250px); /* Ajuste la largeur pour exclure la barre latérale */
    padding: 20px;
    transition: margin-left 0.3s;
}

        .card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 12px rgba(0, 0, 0, 0.2);
        }

        .button-primary {
            background-color: {{ $userSettings->theme_color_primary ?? '#1D4ED8' }};
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: bold;
            text-align: center;
            transition: background-color 0.3s;
        }

        .button-primary:hover {
            background-color: {{ $userSettings->theme_color_secondary ?? '#FF0000' }};
            color: black;
        }

        .form-container {
            display: grid;
            grid-template-columns: 1fr;
            gap: 40px;
        }

        @media (min-width: 768px) {
            .form-container {
                grid-template-columns: 1fr 1fr;
            }
        }

        label {
            font-weight: bold;
            color: #333;
        }

        input[type="text"],
        input[type="email"],
        input[type="file"],
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        input[type="color"],
        select {
            width: 50%;
            padding: 10px;
            border: 3px solid #ddd;
            border-radius: 10px;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="file"]:focus,
        input[type="color"]:focus,
        select:focus {
            border-color: {{ $userSettings->theme_color_primary ?? '#1D4ED8' }};
            box-shadow: 0 0 8px rgba(29, 78, 216, 0.3);
        }
        

        @media only screen and (max-width: 1024px) {
    body > * {
        display: none; /* Cacher tout le contenu du body */
    }

    #tablet-warning {
        display: flex; /* Afficher l'avertissement */
        justify-content: center;
        align-items: center;
        height: 100vh; /* Prendre toute la hauteur de l'écran */
        text-align: center;
        background-color: #f8d7da; /* Fond d'avertissement */
        color: #721c24; /* Couleur du texte d'avertissement */
        font-size: 1.5rem;
        padding: 20px;
    }
}

/* Avertissement caché par défaut */
#tablet-warning {
    display: none;
}
    </style>
</head>

<body>
<div id="tablet-warning" style="display: none; text-align: center; background-color: #f8d7da; color: #721c24; padding: 20px; position: fixed; top: 0; left: 0; right: 0; z-index: 9999;">
{{ __('messages.screen') }}
</div>
<script>
       // Fonction pour vérifier la largeur de l'écran
       function checkScreenWidth() {
        if (window.innerWidth <= 1024) {
            alert("{{ __('messages.screen') }}");
            // Redirection après l'alerte
            window.location.href = '/'; // Redirige vers la page d'accueil
        }
    }

    // Appel initial lors du chargement de la page
    checkScreenWidth();

    // Ajouter un écouteur d'événement pour vérifier lorsque la fenêtre est redimensionnée
    window.addEventListener('resize', checkScreenWidth);
</script>
<div class="sidebar">
    <!-- Dropdown for Dashboard -->
    <div class="dropdown">
        <button class="dropdown-button" onclick="toggleDropdown(this)">
            {{ __('messages.dashboard') }}
        </button>
        <div class="dropdown-content">
            <a href="/">@lang('messages.home')</a>
            <a href="{{ route('calendar.show') }}">@lang('messages.calendar')</a>
            <a href="{{ route('about.index') }}">@lang('messages.about')</a>
            <a href="{{ route('clubinfo') }}">@lang('messages.news')</a>
            <a href="{{ route('press_releases.index') }}">@lang('messages.press_releases')</a>
            <a href="{{ route('teams') }}">@lang('messages.senior_team')</a>
            <a href="{{ route('playersu21.index') }}">@lang('messages.u21_team')</a>
            <a href="{{ route('sponsors.index') }}">@lang('messages.sponsors')</a>
            <a href="{{ route('contact.show') }}">@lang('messages.contact')</a>
        </div>
    </div>

    <a href="{{ route('profile.edit') }}"><i class="fas fa-user-circle"></i> My Account</a>
    <hr>
    <a href="{{ route('coaches.create') }}"><i class="fas fa-user-plus"></i>{{ __('messages.add_coach') }}</a>
    <a href="{{ route('staff.create') }}"><i class="fas fa-user-cog"></i>{{ __('messages.add_staff') }}</a>
    <a href="{{ route('sponsors.create') }}"><i class="fas fa-handshake"></i>{{ __('messages.add_sponsor') }}</a>
    <a href="{{ route('articles.create') }}"><i class="fas fa-newspaper"></i>{{ __('messages.add_news') }}</a>
    <a href="{{ route('press_releases.index') }}"><i class="fas fa-bullhorn"></i>{{ __('messages.add_press_release') }}</a>
    <hr>
    <a href="javascript:void(0);" onclick="showSettings()"><i class="fas fa-cog"></i> Settings</a>
    <a href="javascript:void(0);" onclick="showCard('members-card')"><i class="fas fa-users"></i> Members</a>
    <a href="javascript:void(0);" onclick="showCard('background-card')"><i class="fas fa-images"></i> Backgrounds</a>
    <a href="javascript:void(0);" onclick="showCard('ticket-sales-card')"><i class="fas fa-ticket-alt"></i> Ticket Sales</a>
   <!-- Dropdown for Players -->
   <div class="dropdown">
    <a href="javascript:void(0);" onclick="toggleDropdown(this)" class="dropdown-link flex items-center">
        <i class="fas fa-users"></i>
        <span>Squad</span>
        <i class="fas fa-chevron-down dropdown-icon ml-2"></i>
    </a>
    <div class="dropdown-content players-dropdown">
    <a href="javascript:void(0);" onclick="showSeniorTeam()">{{ __('messages.senior_team') }}</a>
        <a href="javascript:void(0);" onclick="showCard('playersu21-card')">{{ __('messages.u21_team') }}</a>
    </div>

    
</div>
</div>

<script>
        function toggleDropdown(button) {
        // Cibler l'élément suivant, qui devrait être le dropdown content
        const dropdown = button.nextElementSibling;

        // Si l'élément dropdown est trouvé, basculer la classe 'open'
        if (dropdown) {
            dropdown.classList.toggle('open');
        }

        // Basculer la direction de l'icône fléchée
        const icon = button.querySelector('.dropdown-icon');
        if (icon) {
            if (icon.classList.contains('fa-chevron-down')) {
                icon.classList.remove('fa-chevron-down');
                icon.classList.add('fa-chevron-up');
            } else {
                icon.classList.remove('fa-chevron-up');
                icon.classList.add('fa-chevron-down');
            }
        }
    }
</script>

<div class="main-content">
    <header class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold" style="color: {{ $userSettings->theme_color_primary ?? '#1D4ED8' }};">{{ __('messages.dashboard') }}</h1>
    </header>

    <!-- Settings of Dashboard Colors -->
    <div id="settings-form-card" class=" card mt-4" >
    <h2>{{ __('messages.settings') }}</h2>
    <form action="{{ route('user.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="form-container">
            <div>
                <label for="theme_color_primary">{{ __('messages.primary_theme_color') }}</label>
                <input type="color" name="theme_color_primary" id="theme_color_primary" 
                    value="{{ old('theme_color_primary', $userSettings->theme_color_primary ?? '#1D4ED8') }}"
                    style="background-color: {{ old('theme_color_primary', $userSettings->theme_color_primary ?? '#1D4ED8') }};">
                <span class="text-sm font-bold">Chosen color:</span>
                <span id="primary-color-preview" class="color-preview" 
                    style="display: inline-block; width: 20px; height: 20px; background-color: {{ old('theme_color_primary', $userSettings->theme_color_primary ?? '#1D4ED8') }}; margin-left: 10px; border-radius: 50%;"></span>
            </div>
            <div>
                <label for="theme_color_secondary">{{ __('messages.secondary_theme_color') }}</label>
                <input type="color" name="theme_color_secondary" id="theme_color_secondary" 
                    value="{{ old('theme_color_secondary', $userSettings->theme_color_secondary ?? '#FFFFFF') }}"
                    style="background-color: {{ old('theme_color_secondary', $userSettings->theme_color_secondary ?? '#FFFFFF') }};">
                <span class="text-sm font-bold">Chosen color:</span>
                <span id="secondary-color-preview" class="color-preview" 
                    style="display: inline-block; width: 20px; height: 20px; background-color: {{ old('theme_color_secondary', $userSettings->theme_color_secondary ?? '#FFFFFF') }}; margin-left: 10px; border-radius: 50%;"></span>
            </div>
            <div>
                <label for="club_name">{{ __('messages.club_name') }}</label>
                <input type="text" name="club_name" id="club_name" value="{{ old('club_name', $userSettings->club_name ?? '') }}">
            </div>
            <div>
                <label for="logo">{{ __('messages.logo') }}</label>
                <input type="file" name="logo" id="logo" accept="image/*">
                <span class="text-sm font-bold">Chosen logo:</span>
                <img id="logo-preview" src="{{ $userSettings && $userSettings->logo ? asset('storage/' . $userSettings->logo) : asset('unknown.png') }}" alt="{{ __('messages.logo') }}" class="mt-4" style="max-height: 80px; width: auto; margin-left: 10px; border-radius: 5px;">
            </div>
        </div>
        <div class="flex justify-center mt-4">
            <button type="submit" class="button-primary">{{ __('messages.save_settings') }}</button>
        </div>
    </form>
</div>

<script>
    // Update the color preview when the primary color input changes
    document.getElementById('theme_color_primary').addEventListener('input', function() {
        const color = this.value;
        document.getElementById('primary-color-preview').style.backgroundColor = color;
    });

    // Update the color preview when the secondary color input changes
    document.getElementById('theme_color_secondary').addEventListener('input', function() {
        const color = this.value;
        document.getElementById('secondary-color-preview').style.backgroundColor = color;
    });

    // Update the logo preview when a new file is chosen
    document.getElementById('logo').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('logo-preview').src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    });
</script>




<div id="club-info-card" class="card mt-8">
    <h2>{{ __('messages.about') }}</h2>
    <form action="{{ route('club-info.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-container">

            <!-- Sportcomplex Location -->
            <div class="field-container" id="field-sportcomplex_location">
                <label for="sportcomplex_location">{{ __('messages.sportcomplex_location') }}</label>
                <div class="input-with-icon">
                    <input type="text" name="sportcomplex_location" id="sportcomplex_location" value="{{ old('sportcomplex_location', $clubInfo->sportcomplex_location ?? '') }}">
                    <button type="button" class="w-4 ml-2 transform hover:text-red-500 hover:scale-110" onclick="submitDelete('sportcomplex_location')">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>

            <!-- City -->
            <div class="field-container" id="field-city">
                <label for="city">{{ __('messages.city') }}</label>
                <div class="input-with-icon">
                    <input type="text" name="city" id="city" value="{{ old('city', $clubInfo->city ?? '') }}">
                    <button type="button" class="w-4 ml-2 transform hover:text-red-500 hover:scale-110" onclick="submitDelete('city')">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>

            <!-- Phone -->
            <div class="field-container" id="field-phone">
                <label for="phone">{{ __('messages.phone_number') }}</label>
                <div class="input-with-icon">
                    <input type="text" name="phone" id="phone" value="{{ old('phone', $clubInfo->phone ?? '') }}">
                    <button type="button" class="w-4 ml-2 transform hover:text-red-500 hover:scale-110" onclick="submitDelete('phone')">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>

            <!-- Email -->
            <div class="field-container" id="field-email">
                <label for="email">{{ __('messages.email') }}</label>
                <div class="input-with-icon">
                    <input type="email" name="email" id="email" value="{{ old('email', $clubInfo->email ?? '') }}">
                    <button type="button" class="w-4 ml-2 transform hover:text-red-500 hover:scale-110" onclick="submitDelete('email')">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>

            <!-- Organization Logo -->
            <div class="field-container" id="field-organization_logo">
                <label for="organization_logo">{{ __('messages.organization_logo') }}</label>
                <div class="input-with-icon">
                    <input type="file" name="organization_logo" id="organization_logo" accept="image/*">
                    <button type="button" class="w-4 ml-2 transform hover:text-red-500 hover:scale-110" onclick="submitDelete('organization_logo')">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                <span class="text-sm font-bold">Chosen logo:</span>
                <img id="organization-logo-preview" src="{{ $clubInfo && $clubInfo->organization_logo ? asset('storage/' . $clubInfo->organization_logo) : asset('unknown.png') }}" alt="{{ __('messages.logo') }}" class="mt-4" style="max-height: 80px; width: auto; margin-left: 10px; border-radius: 5px;">
            </div>

            <!-- Federation Logo -->
            <div class="field-container" id="field-federation_logo">
                <label for="federation_logo">{{ __('messages.federation_logo') }}</label>
                <div class="input-with-icon">
                    <input type="file" name="federation_logo" id="federation_logo" accept="image/*">
                    <button type="button" class="w-4 ml-2 transform hover:text-red-500 hover:scale-110" onclick="submitDelete('federation_logo')">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                <span class="text-sm font-bold">Chosen logo:</span>
                <img id="federation-logo-preview" src="{{ $clubInfo && $clubInfo->federation_logo ? asset('storage/' . $clubInfo->federation_logo) : asset('unknown.png') }}" alt="{{ __('messages.logo') }}" class="mt-4" style="max-height: 80px; width: auto; margin-left: 10px; border-radius: 5px;">
            </div>

            <!-- Facebook -->
            <div class="field-container" id="field-facebook">
                <label for="facebook">{{ __('messages.facebook') }}</label>
                <div class="input-with-icon">
                    <input type="text" name="facebook" id="facebook" value="{{ old('facebook', $clubInfo->facebook ?? '') }}">
                    <button type="button" class="w-4 ml-2 transform hover:text-red-500 hover:scale-110" onclick="submitDelete('facebook')">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>

            <!-- Instagram -->
            <div class="field-container" id="field-instagram">
                <label for="instagram">{{ __('messages.instagram') }}</label>
                <div class="input-with-icon">
                    <input type="text" name="instagram" id="instagram" value="{{ old('instagram', $clubInfo->instagram ?? '') }}">
                    <button type="button" class="w-4 ml-2 transform hover:text-red-500 hover:scale-110" onclick="submitDelete('instagram')">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>

            <!-- President -->
            <div class="field-container" id="field-president">
                <label for="president">{{ __('messages.president') }}</label>
                <div class="input-with-icon">
                    <input type="text" name="president" id="president" value="{{ old('president', $clubInfo->president ?? '') }}">
                    <button type="button" class="w-4 ml-2 transform hover:text-red-500 hover:scale-110" onclick="submitDelete('president')">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>

            <!-- Latitude -->
            <div class="field-container" id="field-latitude">
                <label for="latitude">{{ __('messages.latitude') }}</label>
                <div class="input-with-icon">
                    <input type="text" name="latitude" id="latitude" value="{{ old('latitude', $clubInfo->latitude ?? '') }}">
                    <button type="button" class="w-4 ml-2 transform hover:text-red-500 hover:scale-110" onclick="submitDelete('latitude')">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>

            <!-- Longitude -->
            <div class="field-container" id="field-longitude">
                <label for="longitude">{{ __('messages.longitude') }}</label>
                <div class="input-with-icon">
                    <input type="text" name="longitude" id="longitude" value="{{ old('longitude', $clubInfo->longitude ?? '') }}">
                    <button type="button" class="w-4 ml-2 transform hover:text-red-500 hover:scale-110" onclick="submitDelete('longitude')">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>

        </div>

        <div class="flex justify-center mt-4">
            <button type="submit" class="button-primary">{{ __('messages.save_club_info') }}</button>
        </div>
    </form>

    <!-- Formulaire de suppression dynamique (invisible) -->
    <form id="delete-form" action="" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
</div>

<script>
    function submitDelete(field) {
        let form = document.getElementById('delete-form');
        form.action = `/club-info/${field}/delete`; // Modifie l'action du formulaire de suppression
        form.submit(); // Soumet le formulaire
    }
</script>




<!-- Inline CSS -->
<style>
    .input-with-icon {
        display: flex;
        align-items: center;
        position: relative;
    }

    .input-with-icon input[type="text"],
    .input-with-icon input[type="email"],
    .input-with-icon input[type="file"] {
        width: 100%;
        padding-right: 2.5rem; /* Espace pour l'icône */
    }

    .delete-icon {
        position: absolute;
        right: 10px;
        cursor: pointer;
        color: red;
    }
</style>

<script>
    // Update the logo preview when a new file is chosen for organization_logo
    document.getElementById('organization_logo').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('organization-logo-preview').src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    });

    // Update the logo preview when a new file is chosen for federation_logo
    document.getElementById('federation_logo').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('federation-logo-preview').src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    });
</script>


      <!-- Section pour ajouter une image de fond -->
<div id="background-card" class="card mt-8" style="display: none;">
    <h2>{{ __('messages.add_background_image') }}</h2>
    <form action="{{ route('dashboard.storeBackgroundImage') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-container">
            <div>
                <label for="background_image">{{ __('messages.select_image') }}</label>
                <input type="file" name="background_image" id="background_image">
            </div>
        </div>
        <div class="flex justify-center mt-4">
            <button type="submit" class="button-primary">{{ __('messages.add') }}</button>
        </div>
    </form>

    <h2>{{ __('messages.assign_background_image') }}</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($backgroundImages as $image)
        <div class="relative rounded-lg overflow-hidden shadow-lg">
            <img src="{{ asset('storage/' . $image->image_path) }}" alt="{{ __('messages.background') }}" class="w-full h-48 object-cover">
            <div class="absolute bottom-0 left-0 right-0 p-4 bg-gradient-to-t from-black to-transparent">
                <form action="{{ route('dashboard.assignBackground') }}" method="POST" class="mt-2">
                    @csrf
                    <div class="flex justify-between items-center">
                        <label for="page_{{ $image->id }}" class="text-white font-bold">{{ __('messages.assign_to_page') }}</label>
                        <select id="page_{{ $image->id }}" name="page" class="border-gray-300 rounded-md shadow-sm focus:ring-{{ $userSettings->theme_color_primary ?? 'blue' }}-500 focus:border-{{ $userSettings->theme_color_primary ?? 'blue' }}-500 bg-white text-gray-800 py-1 px-2">
                            <option value="" @if(is_null($image->assigned_page)) selected @endif>{{ __('messages.none') }}</option>
                            <option value="welcome" @if($image->assigned_page == 'welcome') selected @endif>{{ __('messages.welcome_page') }}</option>
                            <option value="calendar" @if($image->assigned_page == 'calendar') selected @endif>{{ __('messages.calendar_page') }}</option>
                            <option value="about" @if($image->assigned_page == 'about') selected @endif>{{ __('messages.about_page') }}</option>
                            <option value="clubinfo" @if($image->assigned_page == 'clubinfo') selected @endif>{{ __('messages.club_info_page') }}</option>
                            <option value="press_releases" @if($image->assigned_page == 'press_releases') selected @endif>{{ __('messages.press_releases_page') }}</option>
                            <option value="team" @if($image->assigned_page == 'team') selected @endif>{{ __('messages.senior_team_page') }}</option>
                            <option value="teamu21" @if($image->assigned_page == 'teamu21') selected @endif>{{ __('messages.u21_team_page') }}</option>
                            <option value="sponsor" @if($image->assigned_page == 'sponsor') selected @endif>{{ __('messages.sponsor_page') }}</option>
                            <option value="contact" @if($image->assigned_page == 'contact') selected @endif>{{ __('messages.contact_page') }}</option>
                            <option value="fanshop" @if($image->assigned_page == 'fanshop') selected @endif>{{ __('messages.fanshop_page') }}</option>
                        </select>
                        <input type="hidden" name="image_id" value="{{ $image->id }}">
                        <button type="submit" class="ml-2 text-white font-bold py-1 px-4 rounded-full transition duration-200 shadow-lg text-center" style="background-color: {{ $userSettings->theme_color_primary ?? '#1D4ED8' }};">
                            {{ __('messages.apply') }}
                        </button>
                    </div>
                </form>
            </div>
            <form action="{{ route('dashboard.deleteBackgroundImage', $image->id) }}" method="POST" class="absolute top-2 right-2" onsubmit="return confirm('{{ __('messages.delete_confirmation') }}');">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-600 bg-white rounded-full p-1 shadow hover:bg-red-100">
                    &times;
                </button>
            </form>
        </div>
        @endforeach
    </div>
    </div>

<!-- Section Ticket Sales -->
<div id="ticket-sales-card" class="card mt-8 p-4 bg-white shadow-lg rounded-lg">
    <h2 class="text-2xl font-bold mb-4">{{ __('messages.tribunes') }}</h2>
    @if($tribunes->isNotEmpty())
        <div class="tribune-list space-y-6">
            @foreach($tribunes as $tribune)
                <div class="tribune-item p-4 border rounded-lg shadow-md bg-gray-50">
                    <h3 class="text-xl font-semibold flex justify-between items-center">
                        {{ $tribune->name }}
                        <div class="flex">
                            <a href="{{ route('tribunes.edit', $tribune->id) }}" class="w-4 mr-2 transform hover:text-blue-500 hover:scale-110">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('tribunes.destroy', $tribune->id) }}" method="POST" onsubmit="return confirm('{{ __('messages.delete_confirmation') }}');" class="ml-2">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-4 mr-2 transform hover:text-red-500 hover:scale-110">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    </h3>
                    <p class="text-gray-600">{{ $tribune->description }}</p>
                    <p><strong>{{ __('messages.available_seats') }}:</strong> <span class="{{ $tribune->available_seats > 0 ? 'text-green-600' : 'text-red-600 font-bold' }}">{{ $tribune->available_seats }}</span></p>
                    <p><strong>{{ __('messages.tickets_sold') }}:</strong> <span>{{ $tribune->sold_tickets ?? 0 }}</span></p>
                    <p><strong>{{ __('messages.price') }}:</strong> {{ number_format($tribune->price, 2) }} {{ $tribune->currency }}</p>
                    
                    <!-- قسم الحالة -->
                    <p class="font-bold mt-2">
                        <span class="{{ $tribune->available_seats == 0 ? 'text-red-600' : 'text-green-600' }}">
                            {{ $tribune->available_seats == 0 ? __('messages.sold_out') : __('messages.available') }}
                        </span>
                    </p>

                    <!-- قسم الحجوزات -->
                    <h4 class="mt-4 text-md font-semibold">{{ __('messages.reservations') }}:</h4>
                    <ul class="reservation-list space-y-1">
                        @php
                            $reservations = [];
                            $reservationsFile = storage_path('app/reservations.json');
                            if (file_exists($reservationsFile) && filesize($reservationsFile) > 0) {
                                $reservations = json_decode(file_get_contents($reservationsFile), true);
                            }
                        @endphp
                        @foreach($reservations as $reservation)
                            @if($reservation['tribune_name'] == $tribune->name)
                                <li class="text-gray-700">
                                    {{ $reservation['name'] }} - {{ $reservation['seats_reserved'] }} مقاعد ({{ $reservation['email'] }})
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-red-500">{{ __('messages.no_tribunes_available') }}</p>
    @endif
</div>

<!-- Section Players -->
<div id="players-card" class="card mt-8" style="display: none;">
    <h2 class="text-xl font-bold">{{ __('messages.registered_players') }}</h2>
    <div class="overflow-x-auto mt-4">
        <table class="min-w-full bg-white rounded-lg">
            <thead>
                <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                    <th class="py-3 px-6 text-left">{{ __('messages.photo') }}</th>
                    <th class="py-3 px-6 text-left">{{ __('messages.name') }}</th>
                    <th class="py-3 px-6 text-left">{{ __('messages.position') }}</th>
                    <th class="py-3 px-6 text-left">{{ __('messages.number') }}</th>
                    <th class="py-3 px-6 text-left">{{ __('messages.nationality') }}</th>
                    <th class="py-3 px-6 text-left">{{ __('messages.contract_until') }}</th>
                    <th class="py-3 px-6 text-center">{{ __('messages.actions') }}</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm font-light">
                @foreach($players as $player)
                <tr class="border-b border-gray-200 hover:bg-gray-100">
                    <td class="py-3 px-6 text-left">
                        @if($player->photo)
                            <img src="{{ asset('storage/' . $player->photo) }}" alt="{{ $player->first_name }} {{ $player->last_name }}" class="w-16 h-16 rounded-full">
                        @else
                            <img src="{{ asset('avatar.png') }}" alt="{{ __('messages.default_player') }}" class="w-16 h-16 rounded-full">
                        @endif
                    </td>
                    <td class="py-3 px-6 text-left">
                        {{ $player->first_name }} {{ $player->last_name }}
                    </td>
                    <td class="py-3 px-6 text-left">
                        {{ $player->position }}
                    </td>
                    <td class="py-3 px-6 text-left">
                        {{ $player->number }}
                    </td>
                    <td class="py-3 px-6 text-left">
                        {{ $player->nationality }}
                    </td>
                    <td class="py-3 px-6 text-left">
                        {{ \Carbon\Carbon::parse($player->contract_until)->format('d-m-Y') }}
                    </td>
                    <td class="py-3 px-6 text-center">
                        <div class="flex item-center justify-center">
                            <a href="{{ route('players.edit', $player->id) }}" class="w-4 mr-2 transform hover:text-blue-500 hover:scale-110">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('players.destroy', $player->id) }}" method="POST" onsubmit="return confirm('{{ __('messages.delete_confirmation') }}');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-4 mr-2 transform hover:text-red-500 hover:scale-110">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Section Coach -->
<div id="coach-card" class="card mt-8" style="display: none;">
    <h2 class="text-xl font-bold">{{ __('messages.head_coach') }}</h2>
    @if($coach)
    <div class="flex items-center">
        <div class="w-1/4">
            @if($coach->photo)
                <img src="{{ asset('storage/' . $coach->photo) }}" alt="{{ $coach->first_name }} {{ $coach->last_name }}" class="w-24 h-24 rounded-full mx-auto">
            @else
                <img src="{{ asset('avatar.png') }}" alt="{{ __('messages.no_photo') }}" class="w-24 h-24 rounded-full mx-auto">
            @endif
        </div>
        <div class="flex-1 pl-6">
            <h3 class="text-lg font-bold">{{ $coach->first_name }} {{ $coach->last_name }}</h3>
            <p><strong>{{ __('messages.date_of_birth') }}:</strong> {{ \Carbon\Carbon::parse($coach->birth_date)->format('d-m-Y') }}</p>
            <p><strong>{{ __('messages.nationality') }}:</strong> {{ $coach->nationality }}</p>
            <div class="flex mt-4">
                @auth
                    <a href="{{ route('coaches.edit', $coach->id) }}" class="w-4 mr-2 transform hover:text-blue-500 hover:scale-110">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form action="{{ route('coaches.destroy', $coach->id) }}" method="POST" onsubmit="return confirm('{{ __('messages.delete_confirmation') }}');" class="ml-2">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-4 mr-2 transform hover:text-red-500 hover:scale-110">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </form>
                @endauth
            </div>
        </div>
    </div>
    @else
    <p class="text-gray-600 mb-10">{{ __('messages.no_coach') }}</p>
    @auth
    <a href="{{ route('coaches.create') }}" class="button-primary">{{ __('messages.add_coach') }}</a>
    @endauth
    @endif
</div>

<!-- Section Staff -->
<div id="staff-card" class="card mt-8" style="display: none;">
    <h2 class="text-xl font-bold">{{ __('messages.technical_staff') }}</h2>
    @if($staff->isEmpty())
        <p class="text-gray-600">{{ __('messages.no_staff') }}</p>
        @auth
            <a href="{{ route('staff.create') }}" class="button-primary">{{ __('messages.add_staff') }}</a>
        @endauth
    @else
        <div class="overflow-x-auto mt-4">
            <table class="min-w-full bg-white rounded-lg">
                <thead>
                    <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                        <th class="py-3 px-6 text-left">{{ __('messages.photo') }}</th>
                        <th class="py-3 px-6 text-left">{{ __('messages.name') }}</th>
                        <th class="py-3 px-6 text-left">{{ __('messages.position') }}</th> <!-- Correction ici -->
                        <th class="py-3 px-6 text-center">{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 text-sm font-light">
                    @foreach($staff as $member)
                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                        <td class="py-3 px-6 text-left">
                            @if($member->photo)
                                <img src="{{ asset('storage/' . $member->photo) }}" alt="{{ $member->first_name }} {{ $member->last_name }}" class="w-16 h-16 rounded-full">
                            @else
                                <img src="{{ asset('avatar.png') }}" alt="{{ __('messages.default_player') }}" class="w-16 h-16 rounded-full">
                            @endif
                        </td>
                        <td class="py-3 px-6 text-left">
                            {{ $member->first_name }} {{ $member->last_name }}
                        </td>
                        <td class="py-3 px-6 text-left">
                            {{ $member->position }}
                        </td>
                        <td class="py-3 px-6 text-center">
                            @auth
                            <div class="flex item-center justify-center">
                                <a href="{{ route('staff.edit', $member->id) }}" class="w-4 mr-2 transform hover:text-blue-500 hover:scale-110">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('staff.destroy', $member->id) }}" method="POST" onsubmit="return confirm('{{ __('messages.delete_confirmation') }}');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-4 mr-2 transform hover:text-red-500 hover:scale-110">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                            @endauth
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

<!-- Section U21 -->
<div id="playersu21-card" class="card mt-8" style="display: none;">
    <h2 class="text-xl font-bold">{{ __('messages.u21_team') }}</h2>
    <div class="overflow-x-auto mt-4">
        <table class="min-w-full bg-white rounded-lg">
            <thead>
                <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                    <th class="py-3 px-6 text-left">{{ __('messages.photo') }}</th>
                    <th class="py-3 px-6 text-left">{{ __('messages.name') }}</th>
                    <th class="py-3 px-6 text-left">{{ __('messages.position') }}</th>
                    <th class="py-3 px-6 text-left">{{ __('messages.number') }}</th>
                    <th class="py-3 px-6 text-left">{{ __('messages.nationality') }}</th>
                    <th class="py-3 px-6 text-center">{{ __('messages.actions') }}</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm font-light">
                @foreach($playersU21 as $player)
                <tr class="border-b border-gray-200 hover:bg-gray-100">
                    <td class="py-3 px-6 text-left">
                        @if($player->photo)
                            <img src="{{ asset('storage/' . $player->photo) }}" alt="{{ $player->first_name }} {{ $player->last_name }}" class="w-16 h-16 rounded-full">
                        @else
                            <img src="{{ asset('avatar.png') }}" alt="{{ __('messages.default_player') }}" class="w-16 h-16 rounded-full">
                        @endif
                    </td>
                    <td class="py-3 px-6 text-left">
                        {{ $player->first_name }} {{ $player->last_name }}
                    </td>
                    <td class="py-3 px-6 text-left">
                        {{ $player->position }}
                    </td>
                    <td class="py-3 px-6 text-left">
                        {{ $player->number }}
                    </td>
                    <td class="py-3 px-6 text-left">
                        {{ $player->nationality }}
                    </td>
                    <td class="py-3 px-6 text-center">
                        <div class="flex item-center justify-center">
                            <a href="{{ route('playersu21.edit', $player->id) }}" class="w-4 mr-2 transform hover:text-blue-500 hover:scale-110">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('playersu21.destroy', $player->id) }}" method="POST" onsubmit="return confirm('{{ __('messages.delete_confirmation') }}');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-4 mr-2 transform hover:text-red-500 hover:scale-110">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Section pour visualiser tous les membres inscrits -->
<div id="members-card" class="card mt-8" style="display: none;">
    <h2 class="text-xl font-bold">{{ __('messages.registered_members') }}</h2>
    
    <!-- Table pour les membres inscrits -->
    <div class="overflow-x-auto mt-4">
        <table class="min-w-full bg-white rounded-lg">
            <thead>
                <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                    <th class="py-3 px-6 text-left">{{ __('messages.name') }}</th>
                    <th class="py-3 px-6 text-left">{{ __('messages.email') }}</th>
                    <th class="py-3 px-6 text-center">{{ __('messages.registration_date') }}</th>
                    <th class="py-3 px-6 text-center">{{ __('messages.actions') }}</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm font-light">
                @foreach($users as $user)
                <tr class="border-b border-gray-200 hover:bg-gray-100">
                    <td class="py-3 px-6 text-left whitespace-nowrap">
                        <div class="flex items-center">
                            <span class="font-medium">{{ $user->name }}</span>
                        </div>
                    </td>
                    <td class="py-3 px-6 text-left">
                        <div class="flex items-center">
                            <span>{{ $user->email }}</span>
                        </div>
                    </td>
                    <td class="py-3 px-6 text-center">
                        <span>{{ $user->created_at->format('d/m/Y') }}</span>
                    </td>
                    <td class="py-3 px-6 text-center">
                        <div class="flex item-center justify-center">
                            <!-- Bouton pour supprimer l'utilisateur -->
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('{{ __('messages.delete_confirmation') }}');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-4 mr-2 transform hover:text-red-500 hover:scale-110">
                                    <i class="fas fa-trash-alt"></i> <!-- Supprimer -->
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    

    <!-- Formulaire pour ajouter un membre -->
    <form action="{{ route('users.store') }}" method="POST" class="mt-4">
        @csrf
        <div class="form-container">
            <div>
                <label for="name">{{ __('messages.name') }}</label>
                <input type="text" name="name" id="name" required class="w-full p-2 border rounded">
            </div>
            <div>
                <label for="email">{{ __('messages.email') }}</label>
                <input type="email" name="email" id="email" required class="w-full p-2 border rounded">
            </div>
            <div>
                <label for="password">{{ __('messages.password') }}</label>
                <input type="password" name="password" id="password" required class="w-full p-2 border rounded">
            </div>
        </div>
        <div class="flex justify-center mt-4">
            <button type="submit" class="button-primary">{{ __('messages.create_user') }}</button>
        </div>
    </form>

    <!-- Section pour activer ou désactiver l'inscription -->
    <hr class="mt-10 mb-10">
    <h2>{{ __('messages.registration_settings') }}</h2>
    <form action="{{ route('dashboard.updateRegistrationStatus') }}" method="POST">
        @csrf
        <div class="form-container">
            <div>
                <label for="registration_open">{{ __('messages.open_registration') }}</label>
                <select name="registration_open" id="registration_open">
                    <option value="1" {{ $registrationOpen == 'true' ? 'selected' : '' }}>{{ __('messages.yes') }}</option>
                    <option value="0" {{ $registrationOpen == 'false' ? 'selected' : '' }}>{{ __('messages.no') }}</option>
                </select>
            </div>
        </div>
        <div class="flex justify-center mt-4">
            <button type="submit" class="button-primary">{{ __('messages.save') }}</button>
        </div>
    </form>
</div>


    </div>
    <script>
        function toggleDropdown(button) {
            const dropdown = button.parentElement;
            dropdown.classList.toggle('open');
        }

        function showCard(cardId) {
    // Masquer toutes les cards à l'intérieur de `main-content`
    const cards = document.querySelectorAll('.main-content .card');
    cards.forEach(card => {
        if (card.id !== cardId) {
            card.style.display = 'none';
        } else {
            card.style.display = 'block';
        }
    });


    // Afficher uniquement la card spécifiée
    const selectedCard = document.getElementById(cardId);
    if (selectedCard) {
        selectedCard.style.display = 'block';
    }
}

        // Fonction pour afficher toutes les cards (si nécessaire)
        function showAllCards() {
            const cards = document.querySelectorAll('.card');
            cards.forEach(card => card.style.display = 'block');
        }

        function showSeniorTeam() {
    // Masquer toutes les cards
    const cards = document.querySelectorAll('.main-content .card');
    cards.forEach(card => {
        card.style.display = 'none';
    });

    // Afficher les cards des joueurs, entraîneurs et staff
    const playerCard = document.getElementById('players-card');
    const coachCard = document.getElementById('coach-card');
    const staffCard = document.getElementById('staff-card');

    if (playerCard) playerCard.style.display = 'block';
    if (coachCard) coachCard.style.display = 'block';
    if (staffCard) staffCard.style.display = 'block';
}

function showSettings() {
    // Masquer toutes les cards à l'intérieur de `main-content`
    const cards = document.querySelectorAll('.main-content .card');
    cards.forEach(card => {
        card.style.display = 'none';
    });

    // Afficher les cards spécifiées (réglages et club info)
    const settingsFormCard = document.getElementById('settings-form-card');
    const clubInfoCard = document.getElementById('club-info-card');

    if (settingsFormCard) {
        settingsFormCard.style.display = 'block';
    } else {
        console.error('settings-form-card not found');
    }

    if (clubInfoCard) {
        clubInfoCard.style.display = 'block';
    } else {
        console.error('club-info-card not found');
    }
}

document.querySelectorAll('.delete-icon').forEach(icon => {
    icon.addEventListener('click', function () {
        const fieldName = this.dataset.field;
        const clubId = this.dataset.id;

        if (confirm('Are you sure you want to delete this field?')) {
            // Effectuer la requête AJAX pour supprimer le champ
            fetch(`/club-info/${fieldName}/delete`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.message === 'Field deleted successfully.') {
                    // Effacer le champ du formulaire après suppression
                    const inputField = document.getElementById(fieldName);
                    if (inputField) {
                        inputField.value = ''; // Vider le champ
                    }
                } else {
                    console.error('Erreur lors de la suppression du champ.');
                }
            })
            .catch(error => {
                console.error('Erreur lors de la requête de suppression:', error);
            });
        }
    });
});
    </script>
</body>

</html>
