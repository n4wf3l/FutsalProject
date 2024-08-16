<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Coach | {{ $clubName }}</title>
    @if($logoPath)
        <link rel="icon" href="{{ $logoPath }}" type="image/png"> <!-- Type de l'image selon le type du logo -->
    @endif
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
<x-navbar />
    <!-- Header -->
    <header class="text-center my-12" style="margin-top: 20px; font-size:60px;">
        <h1 class="text-6xl text-gray-900">Edit Coach</h1>
        <div class="flex justify-center items-center mt-4">
            <p class="text-xl text-gray-600">Update the coach's information.</p>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container mx-auto mt-8 max-w-lg bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-2xl font-semibold mb-6 text-center text-gray-800">Update Coach Information</h2>

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

        <!-- Edit Form -->
        <form action="{{ route('coaches.update', $coach->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- First Name -->
            <div class="mb-4">
                <label for="first_name" class="block text-sm font-medium text-gray-700">First Name:</label>
                <input type="text" name="first_name" id="first_name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" value="{{ old('first_name', $coach->first_name) }}" required>
            </div>

            <!-- Last Name -->
            <div class="mb-4">
                <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name:</label>
                <input type="text" name="last_name" id="last_name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" value="{{ old('last_name', $coach->last_name) }}" required>
            </div>

            <!-- Birth Date -->
            <div class="mb-4">
                <label for="birth_date" class="block text-sm font-medium text-gray-700">Birth Date:</label>
                <input type="date" name="birth_date" id="birth_date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" value="{{ old('birth_date', $coach->birth_date) }}" required>
            </div>

            <!-- Coaching Since -->
            <div class="mb-4">
                <label for="coaching_since" class="block text-sm font-medium text-gray-700">Coaching Since:</label>
                <input type="date" name="coaching_since" id="coaching_since" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" value="{{ old('coaching_since', $coach->coaching_since) }}" required>
            </div>

            <!-- Birth City -->
            <div class="mb-4">
                <label for="birth_city" class="block text-sm font-medium text-gray-700">Birth City:</label>
                <input type="text" name="birth_city" id="birth_city" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" value="{{ old('birth_city', $coach->birth_city) }}" required>
            </div>

            <!-- Nationality -->
            <div class="mb-4">
                <label for="nationality" class="block text-sm font-medium text-gray-700">Nationality:</label>
                <input type="text" name="nationality" id="nationality" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" value="{{ old('nationality', $coach->nationality) }}" required>
            </div>

            <!-- Description -->
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">Description:</label>
                <textarea name="description" id="description" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" rows="8" style="min-height: 300px; padding: 10px;">{{ old('description', $coach->description) }}</textarea>
            </div>

            <!-- Photo -->
            <div class="mb-4">
                <label for="photo" class="block text-sm font-medium text-gray-700">Photo:</label>
                <input type="file" name="photo" id="photo" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                @if($coach->photo)
                    <img src="{{ asset('storage/' . $coach->photo) }}" alt="Coach Photo" class="mt-2" style="height: 200px; width: auto;">
                @endif
            </div>

            <!-- Submit Button -->
            <div class="text-center">
                <button type="submit" 
                        class="text-white font-bold py-2 px-6 rounded-full focus:outline-none focus:ring-2"
                        style="background-color: {{ $primaryColor }}; border-color: {{ $primaryColor }};"
                        onmouseover="this.style.backgroundColor='{{ $secondaryColor }}';"
                        onmouseout="this.style.backgroundColor='{{ $primaryColor }}';">
                    Update Coach
                </button>
            </div>
        </form>
    </div>
    <x-footer />

    <!-- CKEditor script -->
    <script src="https://cdn.ckeditor.com/ckeditor5/34.1.0/classic/ckeditor.js"></script>
    <script>
        ClassicEditor
        .create(document.querySelector('#description'), {
            toolbar: {
                items: [
                    'bold', 'italic', '|',
                    'bulletedList', 'numberedList', '|',
                    'undo', 'redo', '|',
                    'blockQuote', 'insertTable', 'heading', '|',
                    'link', 'textColor', 'highlight'
                ]
            },
        })
        .catch(error => {
            console.error(error);
        });
    </script>
</body>
</html>
