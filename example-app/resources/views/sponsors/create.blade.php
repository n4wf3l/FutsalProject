<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Sponsor | {{ $clubName }}</title>
    @if($logoPath)
        <link rel="icon" href="{{ $logoPath }}" type="image/png"> <!-- Type de l'image selon le type du logo -->
    @endif
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-100">
    <x-navbar />

    <header class="text-center my-12">
        <x-page-title subtitle="">
            Add New Sponsor
        </x-page-title>
    </header>

    <div class="container mx-auto mt-8 p-8 rounded-lg shadow-md border border-gray-300 max-w-3xl" style="margin-bottom: 50px; background-color: #f9f9f9;">

        @if ($errors->any())
            <div class="form-error mb-4 p-4 text-white bg-red-500 rounded-lg shadow-md">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('sponsors.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 gap-6 mb-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Name:</label>
                    <input type="text" name="name" id="name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" value="{{ old('name') }}" required>
                </div>

                <div>
                    <label for="logo" class="block text-sm font-medium text-gray-700">Logo:</label>
                    <input type="file" name="logo" id="logo" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label for="website" class="block text-sm font-medium text-gray-700">Website:</label>
                    <input type="text" name="website" id="website" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="www.example.com" value="{{ old('website') }}">
                </div>
            </div>

            <div class="flex justify-center gap-4">
                <button type="submit" class="text-white font-bold py-2 px-6 rounded-full shadow-lg transition duration-200"
                    style="background-color: {{ $primaryColor }}; font-size: 15px;"
                    onmouseover="this.style.backgroundColor='{{ $secondaryColor }}'"
                    onmouseout="this.style.backgroundColor='{{ $primaryColor }}'">
                    Save Sponsor
                </button>
                <a href="{{ route('sponsors.index') }}"
                    class="text-white font-bold py-2 px-6 rounded-full shadow-lg transition duration-200"
                    style="background-color: {{ $primaryColor }}; font-size: 15px;"
                    onmouseover="this.style.backgroundColor='{{ $secondaryColor }}'"
                    onmouseout="this.style.backgroundColor='{{ $primaryColor }}'">
                    Cancel
                </a>
            </div>
        </form>
    </div>

    <x-footer />
</body>

</html>
