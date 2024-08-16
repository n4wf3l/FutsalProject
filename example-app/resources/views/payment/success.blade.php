<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Success</title>
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
            width: 80%; /* Ajustez la largeur du hr selon vos besoins */
            margin-left: auto;
            margin-right: auto;
        }

        .qr-code-container {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .qr-code-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
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

        <h1 class="text-3xl font-bold mb-6 text-green-500">Payment Successful!</h1>

        <div class="bg-white p-6 rounded-lg shadow-lg inline-block">
            <h2 class="text-2xl font-semibold mb-4">Your Reservation Details</h2>
            <p><strong>Name:</strong> {{ $reservationDetails['name'] }}</p>
            <p><strong>Email:</strong> {{ $reservationDetails['email'] }}</p>
            <p><strong>Amount Paid:</strong> {{ number_format($reservationDetails['amount'], 2) }} {{ $reservationDetails['currency'] }}</p>
            <p><strong>Date:</strong> {{ $reservationDetails['date'] }}</p>
            <p><strong>Reservation ID:</strong> {{ $reservationDetails['reservation_id'] }}</p>
        </div>

        <div class="qr-code-container">
            <div class="qr-code-wrapper">
                <h3 class="text-xl font-semibold mb-2">Scan this QR code for your reservation details:</h3>
                {!! $qrCode !!}
            </div>
        </div>

        <div class="mt-6">
            <a href="{{ route('fanshop.index') }}" class="back-button">Back to Fanshop</a>
        </div>
    </div>
</body>
</html>
