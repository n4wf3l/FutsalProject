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
    <style>
        .add-sponsor-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 2rem;
            background-color: #f9f9f9;
            border-radius: 0.5rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .add-sponsor-title {
            font-size: 2.5rem; /* Grandeur du titre conservée */
            font-weight: bold;
            text-align: center;
            margin-bottom: 1.5rem;
            color: {{ $primaryColor }};
        }

        .form-label {
            font-weight: 500;
            color: #333;
            font-size: 1.2rem;
            margin-bottom: 0.5rem;
        }

        .form-input {
            width: 100%;
            padding: 1rem;
            border-radius: 0.5rem;
            border: 1px solid #ccc;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1) inset;
            font-size: 1.1rem;
            transition: border-color 0.3s ease;
        }

        .form-input:focus {
            border-color: #1D4ED8;
            outline: none;
        }

        .form-error {
            background-color: #f44336;
            color: white;
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
        }

        .button-group {
            display: flex;
            justify-content: center; /* Centrage des boutons */
            gap: 16px;
            margin-top: 2rem;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }
    </style>
</head>

<body class="bg-gray-100">
    <x-navbar />

    <div class="add-sponsor-container">
        <h1 class="add-sponsor-title">Add New Sponsor</h1>

        @if ($errors->any())
        <div class="form-error">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('sponsors.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-grid">
                <div class="mb-4">
                    <label for="name" class="form-label">Name:</label>
                    <input type="text" name="name" id="name" class="form-input" value="{{ old('name') }}" required>
                </div>

                <div class="mb-4">
                    <label for="logo" class="form-label">Logo:</label>
                    <input type="file" name="logo" id="logo" class="form-input">
                </div>

                <div class="mb-4">
    <label for="website" class="form-label">Website:</label>
    <input type="text" name="website" id="website" class="form-input flex-grow" placeholder="www.example.com" value="{{ old('website') }}">
</div>
            </div>

            <div class="button-group">
                <button type="submit" class="text-white font-bold py-2 px-6 rounded-full transition duration-200 shadow-lg text-center"
                    style="background-color: {{ $primaryColor }};"
                    onmouseover="this.style.backgroundColor='{{ $secondaryColor }}'"
                    onmouseout="this.style.backgroundColor='{{ $primaryColor }}'">Save Sponsor</button>
                <a href="{{ route('sponsors.index') }}"
                    class="text-white font-bold py-2 px-6 rounded-full transition duration-200 shadow-lg text-center"
                    style="background-color: {{ $primaryColor }};"
                    onmouseover="this.style.backgroundColor='{{ $secondaryColor }}'"
                    onmouseout="this.style.backgroundColor='{{ $primaryColor }}'">Cancel</a>
            </div>
        </form>
    </div>

    <x-footer />
</body>

</html>
