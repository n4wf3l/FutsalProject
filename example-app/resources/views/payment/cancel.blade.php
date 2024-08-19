<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Canceled | {{ $clubName }}</title>
    @if($logoPath)
        <link rel="icon" href="{{ $logoPath }}" type="image/png">
    @endif
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @vite('resources/css/app.css')
    <style>
        .logo-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 20px;
        }

        .logo-container img {
            max-width: 200px; /* Ajustez la taille du logo selon vos besoins */
            height: auto;
        }

        .club-name {
            font-size: 24px;
            font-weight: bold;
            margin-top: 10px;
            color: #333;
        }

        hr {
            border: none;
            border-top: 2px solid {{ $primaryColor }};
            margin-bottom: 40px;
            width: 80%;
            margin-left: auto;
            margin-right: auto;
        }

        .back-button {
            background-color: {{ $primaryColor }};
            color: white;
            font-weight: bold;
            padding: 10px 20px;
            border-radius: 8px;
            transition: background-color 0.3s;
        }

        .back-button:hover {
            background-color: {{ $secondaryColor }};
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto py-12 text-center">
        <!-- Section du logo -->
        @if($logoPath)
        <div class="logo-container">
            <img src="{{ asset($logoPath) }}" alt="Club Logo">
            <div class="club-name">{{ $clubName }}</div>
        </div>
        <hr>
        @endif

        <h1 class="text-3xl font-bold text-red-600 mb-6">Payment Canceled</h1>

        <div class="bg-white p-6 rounded-lg shadow-lg inline-block">
            <p class="text-lg mb-4">Your payment was canceled. If you have any questions, please contact support.</p>
            <a href="{{ route('fanshop.index') }}" class="back-button">Return to Fanshop</a>
        </div>
    </div>
</body>
</html>
