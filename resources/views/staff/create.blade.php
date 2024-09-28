<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Staff Member | {{ $clubName }}</title>
    @if($logoPath)
        <link rel="icon" href="{{ $logoPath }}" type="image/png"> <!-- Type de l'image selon le type du logo -->
    @endif
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
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
    <header class="text-center my-12" style="margin-top: 20px; font-size:60px;">
        <h1 class="text-6xl text-gray-900">Add Staff Member</h1>
        <div class="flex justify-center items-center mt-4">
            <p class="text-xl text-gray-600">Enter the details of the new staff member.</p>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container mx-auto mt-8 max-w-lg bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-2xl font-semibold mb-6 text-center text-gray-800">Add New Staff</h2>

        <!-- Error Messages -->
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

        <!-- Add Form -->
        <form action="{{ route('staff.store') }}" method="POST">
            @csrf

            <!-- First Name -->
            <div class="mb-4">
                <label for="first_name" class="block text-sm font-medium text-gray-700">First Name:</label>
                <input type="text" name="first_name" id="first_name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
            </div>

            <!-- Last Name -->
            <div class="mb-4">
                <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name:</label>
                <input type="text" name="last_name" id="last_name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
            </div>

            <!-- Position -->
            <div class="mb-4">
                <label for="position" class="block text-sm font-medium text-gray-700">Position:</label>
                <input type="text" name="position" id="position" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
            </div>

            <!-- Submit Button -->
            <div class="text-center">
                <button type="submit" 
                        class="text-white font-bold py-2 px-6 rounded-full focus:outline-none focus:ring-2"
                        style="background-color: {{ $primaryColor }}; border-color: {{ $primaryColor }};"
                        onmouseover="this.style.backgroundColor='{{ $secondaryColor }}';"
                        onmouseout="this.style.backgroundColor='{{ $primaryColor }}';">
                    Add Staff Member
                </button>
            </div>
        </form>
    </div>
    <x-footer />
</body>
</html>