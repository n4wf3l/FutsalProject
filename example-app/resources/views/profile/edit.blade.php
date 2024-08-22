<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile | {{ $clubName }}</title>
    @if($logoPath)
        <link rel="icon" href="{{ $logoPath }}" type="image/png">
    @endif
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 dark:bg-gray-900">
    <x-navbar />

    <header class="text-center my-12">
        <x-page-title subtitle="Manage your profile and account settings">
            {{ __('Profile') }}
        </x-page-title>
    </header>

    <main class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Update Profile Information -->
            <div class="p-8 bg-white dark:bg-gray-800 shadow-lg rounded-lg">
                <div class="max-w-2xl mx-auto">
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-4">
                        {{ __('Update Profile Information') }}
                    </h3>
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Update Password -->
            <div class="p-8 bg-white dark:bg-gray-800 shadow-lg rounded-lg">
                <div class="max-w-2xl mx-auto">
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-4">
                        {{ __('Update Password') }}
                    </h3>
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Delete User Account -->
            <div class="p-8 bg-white dark:bg-gray-800 shadow-lg rounded-lg">
                <div class="max-w-2xl mx-auto">
                    <h3 class="text-lg font-semibold text-red-600 dark:text-red-400 mb-4">
                        {{ __('Delete Account') }}
                    </h3>
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </main>

    <x-footer />

    <!-- Ajout des scripts si nécessaire -->
    @vite('resources/js/app.js')
</body>
</html>
