@php
    $backgroundImage = \App\Models\BackgroundImage::where('assigned_page', 'welcome')->first();
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome | {{ $clubName }}</title>

    <!-- Favicon -->
    @if($logoPath)
        <link rel="icon" href="{{ $logoPath }}" type="image/png">
    @endif

    <!-- CSS Bootstrap pour les Modals -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- CSS App -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @vite('resources/css/app.css')

    <style>
        .background-container {
            z-index: 900;
            position: relative;
            width: 100%;
            height: 100vh;
            @if($backgroundImage)
                background: url('{{ asset('storage/' . $backgroundImage->image_path) }}') no-repeat center center fixed;
                background-size: cover;
            @else
                background-color: #f8f9fa; /* Default background color if no image is available */
            @endif
        }

        @if($backgroundImage)
        .background-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5); /* Overlay with transparency */
            z-index: 1;
        }
        @endif

        .content-container {
            position: relative;
            z-index: 2;
            color: white;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .flash-message-container {
            position: relative;
            overflow: hidden;
            width: 100%;
            background-color: {{ $secondaryColor }};
            color: white;
            padding: 10px 0;
            z-index: 1000;
            display: flex;
            align-items: center; /* Aligns vertically */
        }

        .flash-message {
            display: inline-block;
            white-space: nowrap;
            animation: marquee 20s linear infinite;
            transform: translateX(100%); /* Start from outside the right edge */
        }

        .weather-info {
            background-color: {{ $secondaryColor }};
            padding: 10px;
            font-weight: bold;
            z-index: 1001;
        }

        @keyframes marquee {
            0% { transform: translateX(100%); } /* Start from outside the right edge */
            100% { transform: translateX(-100%); } /* Move completely to the left outside of the view */
        }
    </style>
</head>
<body class="bg-gray-100">
    <!-- Navbar -->
    <x-navbar />

    <!-- Flash Message sous la Navbar -->
    <div class="flash-message-container">
        <div class="weather-info">
            {{ $city }}: {{ $weatherData['main']['temp'] ?? 'N/A' }}°C |
        </div>
        <div class="flash-message">
            {{ session('flash_message', 'Bienvenue sur notre site web !') }}
        </div>
    </div>

    <!-- Background Container -->
    <div class="background-container">
        <!-- Page Content -->
        <div class="container mt-5 content-container">
            <h1>Welcome to {{ $clubName }}</h1>
            <p>Current Weather in {{ $city }}: {{ $weatherData['main']['temp'] ?? 'N/A' }}°C</p>
            <!-- Ajoutez d'autres contenus ici -->
        </div>
    </div>

    <!-- Footer -->
    <x-footer />

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
