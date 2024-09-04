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
    @vite('resources/css/app.css')
    <style>
        .button-hover-primary {
            background-color: {{ $userSettings->theme_color_primary ?? '#1D4ED8' }};
            color: white;
        }
        .button-hover-primary:hover {
            background-color: {{ $userSettings->theme_color_secondary ?? '#FFFFFF' }};
            color: black;
        }

        /* Layout container styles */
        .layout-container {
            display: flex;
            align-items: flex-start;
            gap: 20px;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }

        /* Sidebar dropdown styles */
        .dropdown-container {
            position: relative;
            display: inline-block;
            text-align: center;
            margin-bottom: 20px;
        }

        #dropdownButton {
            background-color: {{ $userSettings->theme_color_primary ?? '#1D4ED8' }};
            color: white;
            padding: 10px 20px;
            font-size: 16px;
            font-weight: bold;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .sidebar {
            position: absolute;
            top: 100%; /* Position the dropdown below the button */
            left: 50%;
            transform: translateX(-50%);
            width: 200px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            padding: 10px 0;
            z-index: 1000;
            display: none; /* Hide initially */
            transition: transform 0.3s ease-in-out;
        }

        .sidebar.show {
            display: block;
        }

        .sidebar a {
            display: block;
            padding: 10px 20px;
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
            flex: 1;
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
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
        }

        @media (min-width: 768px) {
            .form-container {
                grid-template-columns: 1fr 1fr;
            }
        }

        /* New styles for the white section under the navbar */
        .header-section {
            background-color: white;
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.1);
        }

        @media (min-width: 768px) {
            .header-section {
                flex-direction: row;
                padding: 20px 15px;
            }
        }

        .header-section h1 {
            margin: 0;
            color: {{ $userSettings->theme_color_primary ?? '#1D4ED8' }};
            font-size: 2rem;
            font-weight: bold;
            text-align: center;
        }

        .header-section p {
            margin: 0;
            color: grey;
            font-size: 1rem;
            text-align: center;
        }

        .header-section img {
            height: 50px;
            width: 50px;
            object-fit: cover;
            border-radius: 50%;
        }

        .header-section .account-section {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 15px;
        }
    </style>
</head>
<body>
    <x-navbar />

    <div class="header-section">
        <div>
            <x-page-title subtitle="{{ __('messages.welcome_dashboard', ['name' => Auth::user()->name]) }}">
                {{ __('messages.dashboard') }}
            </x-page-title>
        </div>
        <div class="account-section">
            <a href="{{ route('profile.edit') }}">
                <img src="{{ asset('account.png') }}" alt="{{ __('messages.logo') }}">
            </a>
        </div>
    </div>

    <div class="dropdown-container">
        <button id="dropdownButton" class="mt-20">
            &#x25BC; {{ __('messages.menu') }}
        </button>
        <div id="sidebar" class="sidebar">
            <a href="{{ route('coaches.create') }}">{{ __('messages.add_coach') }}</a>
            <a href="{{ route('staff.create') }}">{{ __('messages.add_staff') }}</a>
            <a href="{{ route('sponsors.create') }}">{{ __('messages.add_sponsor') }}</a>
            <a href="{{ route('articles.create') }}">{{ __('messages.add_news') }}</a>
            <a href="{{ route('press_releases.index') }}">{{ __('messages.add_press_release') }}</a>
        </div>
    </div>

    <div class="layout-container">
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

            <div class="form-container">
                <div class="card">
                    <form action="{{ route('user.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                        @csrf
                        @method('PUT')

                        <div class="mb-6 flex flex-col items-center">
                            <label for="theme_color_primary" class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.primary_theme_color') }}</label>
                            <input type="color" name="theme_color_primary" id="theme_color_primary" class="block max-w-xs border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" value="{{ old('theme_color_primary', $userSettings->theme_color_primary ?? '#000000') }}">
                        </div>

                        <div class="mb-6 flex flex-col items-center">
                            <label for="theme_color_secondary" class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.secondary_theme_color') }}</label>
                            <input type="color" name="theme_color_secondary" id="theme_color_secondary" class="block max-w-xs border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" value="{{ old('theme_color_secondary', $userSettings->theme_color_secondary ?? '#FFFFFF') }}">
                        </div>

                        <div class="mb-6 flex flex-col items-center">
                            <label for="club_name" class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.club_name') }}</label>
                            <input type="text" name="club_name" id="club_name" class="block max-w-md border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" value="{{ old('club_name', $userSettings->club_name ?? '') }}">
                        </div>

                        <div class="mb-6 flex flex-col items-center">
                            <label for="logo" class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.logo') }}</label>
                            <input type="file" name="logo" id="logo" class="block max-w-md border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            @if($userSettings && $userSettings->logo)
                                <img src="{{ asset('storage/' . $userSettings->logo) }}" alt="{{ __('messages.logo') }}" class="mt-4" style="max-height: 80px; width: auto;">
                            @endif
                        </div>

                        <div class="flex justify-center">
                            <button type="submit" style="background-color: {{ $userSettings->theme_color_primary ?? '#1D4ED8' }}; margin-bottom:20px;" class="text-white font-bold py-2 px-6 rounded-full hover:opacity-80 transition duration-200 shadow-lg text-center">
                                {{ __('messages.save_settings') }}
                            </button>
                        </div>
                    </form>
                </div>

                <div class="card">
                    <form action="{{ route('club-info.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                        @csrf

                        <div class="mb-6 flex flex-col items-center">
                            <label for="sportcomplex_location" class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.sportcomplex_location') }}</label>
                            <input type="text" name="sportcomplex_location" id="sportcomplex_location" class="block max-w-md border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" value="{{ old('sportcomplex_location', $clubInfo->sportcomplex_location ?? '') }}">
                        </div>

                        <div class="mb-6 flex flex-col items-center">
                            <label for="city" class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.city') }}</label>
                            <input type="text" name="city" id="city" class="block max-w-md border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" value="{{ old('city', $clubInfo->city ?? '') }}">
                        </div>

                        <div class="mb-6 flex flex-col items-center">
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.phone_number') }}</label>
                            <input type="text" name="phone" id="phone" class="block max-w-md border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" value="{{ old('phone', $clubInfo->phone ?? '') }}">
                        </div>

                        <div class="mb-6 flex flex-col items-center">
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.email') }}</label>
                            <input type="email" name="email" id="email" class="block max-w-md border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" value="{{ old('email', $clubInfo->email ?? '') }}">
                        </div>

                        <div class="mb-6 flex flex-col items-center">
                            <label for="federation_logo" class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.federation_logo') }}</label>
                            <input type="file" name="federation_logo" id="federation_logo" class="block max-w-md border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            @if($clubInfo && $clubInfo->federation_logo)
                                <img src="{{ asset('storage/' . $clubInfo->federation_logo) }}" alt="{{ __('messages.logo') }}" class="mt-4" style="max-height: 80px; width: auto;">
                            @endif
                        </div>

                        <div class="mb-6 flex flex-col items-center">
                            <label for="facebook" class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.facebook') }}</label>
                            <input type="text" name="facebook" id="facebook" class="block max-w-md border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" value="{{ old('facebook', $clubInfo->facebook ?? '') }}">
                        </div>

                        <div class="mb-6 flex flex-col items-center">
                            <label for="instagram" class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.instagram') }}</label>
                            <input type="text" name="instagram" id="instagram" class="block max-w-md border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" value="{{ old('instagram', $clubInfo->instagram ?? '') }}">
                        </div>

                        <div class="mb-6 flex flex-col items-center">
                            <label for="president" class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.president') }}</label>
                            <input type="text" name="president" id="president" class="block max-w-md border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" value="{{ old('president', $clubInfo->president ?? '') }}">
                        </div>

                        <div class="mb-6 flex flex-col items-center">
                            <label for="latitude" class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.latitude') }}</label>
                            <input type="text" name="latitude" id="latitude" class="block max-w-md border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" value="{{ old('latitude', $clubInfo->latitude ?? '') }}">
                        </div>

                        <div class="mb-6 flex flex-col items-center">
                            <label for="longitude" class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.longitude') }}</label>
                            <input type="text" name="longitude" id="longitude" class="block max-w-md border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" value="{{ old('longitude', $clubInfo->longitude ?? '') }}">
                        </div>

                        <div class="flex justify-center">
                            <button type="submit" style="background-color: {{ $userSettings->theme_color_primary ?? '#1D4ED8' }}; margin-bottom:20px;" class="text-white font-bold py-2 px-6 rounded-full hover:opacity-80 transition duration-200 shadow-lg text-center">
                                {{ __('messages.save_club_info') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <section class="mb-16">
                <div class="card">
                    <h2 class="text-xl font-semibold text-center mb-6" style="color: {{ $userSettings->theme_color_primary ?? '#1D4ED8' }};">
                        {{ __('messages.add_background_image') }}
                    </h2>
                    <form action="{{ route('dashboard.storeBackgroundImage') }}" method="POST" enctype="multipart/form-data" class="space-y-6" onsubmit="return validateForm()">
                        @csrf
                        <div class="mb-6">
                            <label for="background_image" class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.select_image') }}</label>
                            <input type="file" name="background_image" id="background_image" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-{{ $userSettings->theme_color_primary ?? 'blue' }}-500 focus:border-{{ $userSettings->theme_color_primary ?? 'blue' }}-500">
                        </div>
                        <div class="flex justify-center">
                            <button type="submit" style="background-color: {{ $userSettings->theme_color_primary ?? '#1D4ED8' }};" class="text-white font-bold py-2 px-6 rounded-full hover:opacity-90 transition duration-200 shadow-lg text-center">
                                {{ __('messages.add') }}
                            </button>
                        </div>
                    </form>
                </div>
            </section>

            <script>
                function validateForm() {
                    const fileInput = document.getElementById('background_image');
                    if (fileInput.files.length === 0) {
                        alert('{{ __('messages.select_image_alert') }}');
                        return false;
                    }
                    return true;
                }

                document.getElementById('dropdownButton').addEventListener('click', function () {
                    const sidebar = document.getElementById('sidebar');
                    sidebar.classList.toggle('show');
                    const button = document.getElementById('dropdownButton');
                    if (sidebar.classList.contains('show')) {
                        button.innerHTML = '&#x25B2; {{ __('messages.menu') }}';
                    } else {
                        button.innerHTML = '&#x25BC; {{ __('messages.menu') }}';
                    }
                });

                document.addEventListener('click', function (event) {
                    const dropdownButton = document.getElementById('dropdownButton');
                    const sidebar = document.getElementById('sidebar');
                    if (!dropdownButton.contains(event.target) && !sidebar.contains(event.target)) {
                        sidebar.classList.remove('show');
                        dropdownButton.innerHTML = '&#x25BC; {{ __('messages.menu') }}';
                    }
                });
            </script>

            <section class="mb-16">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($backgroundImages as $image)
                        <div class="relative rounded-lg overflow-hidden shadow-lg">
                            <img src="{{ asset('storage/' . $image->image_path) }}" alt="{{ __('messages.background') }}" class="w-full h-48 object-cover">
                            <div class="absolute bottom-0 left-0 right-0 p-4 bg-gradient-to-t from-black to-transparent">
                                <form action="{{ route('dashboard.assignBackground') }}" method="POST" class="mt-2">
                                    @csrf
                                    <div class="flex justify-between items-center">
                                        <select name="page" class="border-gray-300 rounded-md shadow-sm focus:ring-{{ $userSettings->theme_color_primary ?? 'blue' }}-500 focus:border-{{ $userSettings->theme_color_primary ?? 'blue' }}-500 bg-white text-gray-800 py-1 px-2">
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
                                            <!-- Add more pages here -->
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
            </section>

            <section>
                <div class="card">
                    <h3 class="text-lg font-medium text-gray-700 mb-4">{{ __('messages.registration_settings') }}</h3>
                    <form action="{{ route('dashboard.updateRegistrationStatus') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label for="registration_open" class="block text-sm font-medium text-gray-700">{{ __('messages.open_registration') }}</label>
                            <select name="registration_open" id="registration_open" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="1" {{ $registrationOpen == 'true' ? 'selected' : '' }}>{{ __('messages.yes') }}</option>
                                <option value="0" {{ $registrationOpen == 'false' ? 'selected' : '' }}>{{ __('messages.no') }}</option>
                            </select>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="text-white font-bold py-2 px-4 rounded-full" style="background-color: {{ $userSettings->theme_color_primary ?? '#1D4ED8' }};">
                                {{ __('messages.save') }}
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
