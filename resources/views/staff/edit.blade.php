<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.edit_staff_member_title') }} | {{ $clubName }}</title>
    @if($logoPath)
        <link rel="icon" href="{{ $logoPath }}" type="image/png">
    @endif
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100">

    <x-navbar />

    <!-- Header -->
    <header class="text-center my-12">
        <x-page-title subtitle="{{ __('messages.edit_staff_member_subtitle') }}">
            {{ __('messages.edit_staff_member_title') }}
        </x-page-title>
    </header>

    <!-- Main Content -->
    <div class="container mx-auto mt-8 max-w-lg bg-white p-6 rounded-lg shadow-md" style="margin-bottom: 50px;">
        <h2 class="text-2xl font-semibold mb-6 text-center text-gray-800">{{ __('messages.update_information') }}</h2>

        <!-- Error Messages -->
        @if ($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                <p class="font-bold">{{ __('messages.fix_errors') }}:</p>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Edit Form -->
        <form action="{{ route('staff.update', $staff->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- First Name -->
            <div class="mb-4">
                <label for="first_name" class="block text-sm font-medium text-gray-700">{{ __('messages.first_name') }}:</label>
                <input type="text" name="first_name" id="first_name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" value="{{ old('first_name', $staff->first_name) }}" required>
            </div>

            <!-- Last Name -->
            <div class="mb-4">
                <label for="last_name" class="block text-sm font-medium text-gray-700">{{ __('messages.last_name') }}:</label>
                <input type="text" name="last_name" id="last_name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" value="{{ old('last_name', $staff->last_name) }}" required>
            </div>

            <!-- Position -->
            <div class="mb-4">
                <label for="position" class="block text-sm font-medium text-gray-700">{{ __('messages.position') }}:</label>
                <input type="text" name="position" id="position" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" value="{{ old('position', $staff->position) }}" required>
            </div>

            <!-- Submit Button -->
            <div class="text-center">
                <button type="submit" 
                        class="text-white font-bold py-2 px-6 rounded-full shadow-lg transition duration-200"
                        style="
                            background-color: {{ $primaryColor }};
                            margin-bottom: 20px; 
                            font-size: 15px;
                            transition: background-color 0.3s ease;
                        "
                        onmouseover="this.style.backgroundColor='{{ $secondaryColor }}'"
                        onmouseout="this.style.backgroundColor='{{ $primaryColor }}'">
                    {{ __('messages.update_staff_member_button') }}
                </button>
            </div>
        </form>
    </div>

    <x-footer />
</body>
</html>
