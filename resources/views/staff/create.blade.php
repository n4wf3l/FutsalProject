<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.add_staff_member') }} | {{ $clubName }}</title>
    @if($logoPath)
        <link rel="icon" href="{{ $logoPath }}" type="image/png"> <!-- Type de l'image selon le type du logo -->
    @endif
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @vite('resources/css/app.css')
    <style>
        .button-hover-primary {
            background-color: {{ $primaryColor }};
        }
        .button-hover-primary:hover {
            background-color: {{ $secondaryColor }};
        }
    </style>
</head>
<body class="bg-gray-100">
    <x-navbar />

    <!-- Header -->
    <header class="text-center my-12">
        <x-page-title subtitle="">
            {{ __('messages.add_staff_member') }}
        </x-page-title>
    </header>

    <!-- Main Content -->
    <div class="container mx-auto mt-8 max-w-lg bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-2xl font-semibold mb-6 text-center text-gray-800">{{ __('messages.add_new_staff') }}</h2>

        <!-- Error Messages -->
        @if ($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                <p class="font-bold">{{ __('messages.please_fix_errors') }}</p>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Add Form -->
        <form action="{{ route('staff.store') }}" method="POST">
            @csrf

            <!-- First Name -->
            <div class="mb-4">
                <label for="first_name" class="block text-sm font-medium text-gray-700">{{ __('messages.first_name') }}:</label>
                <input type="text" name="first_name" id="first_name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-{{ $primaryColor }} focus:border-{{ $primaryColor }}" required>
            </div>

            <!-- Last Name -->
            <div class="mb-4">
                <label for="last_name" class="block text-sm font-medium text-gray-700">{{ __('messages.last_name') }}:</label>
                <input type="text" name="last_name" id="last_name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-{{ $primaryColor }} focus:border-{{ $primaryColor }}" required>
            </div>

            <!-- Position -->
            <div class="mb-4">
                <label for="position" class="block text-sm font-medium text-gray-700">{{ __('messages.position') }}:</label>
                <input type="text" name="position" id="position" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-{{ $primaryColor }} focus:border-{{ $primaryColor }}" required>
            </div>

            <!-- Submit Button -->
            <div class="text-center">
                <button type="submit" 
                        class="text-white font-bold py-2 px-6 rounded-full focus:outline-none focus:ring-2 button-hover-primary"
                        style="border-color: {{ $primaryColor }};">
                    {{ __('messages.add_staff_member') }}
                </button>
            </div>
        </form>
    </div>
    <x-footer />
</body>
</html>
