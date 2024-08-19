<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Success | {{ $clubName }}</title>
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

        @keyframes blink {
        0% {
            background-color: {{ $primaryColor }};
        }
        50% {
            background-color: {{ $secondaryColor }};
        }
        100% {
            background-color: {{ $primaryColor }};
        }
    }

    .pdf-button {
        color: white;
        font-weight: bold;
        padding: 10px 20px;
        border-radius: 8px;
        text-decoration: none;
        animation: blink 1.5s infinite;
        transition: background-color 0.3s;
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
            @if($reservationDetails['game'])
                <p><strong>Match:</strong> {{ $reservationDetails['game']->homeTeam->name }} vs {{ $reservationDetails['game']->awayTeam->name }}</p>
                <p><strong>Date of Match:</strong> {{ \Carbon\Carbon::parse($reservationDetails['game']->match_date)->format('d-m-Y') }}</p>
            @endif
            <p><strong>Seats Reserved:</strong> {{ $reservationDetails['seats_reserved'] }}</p>
        </div>

        <div class="qr-code-container">
            <div class="qr-code-wrapper">
                <h3 class="text-xl font-semibold mb-2">Scan this QR code for your reservation details:</h3>
                <img src="data:image/svg+xml;base64,{{ $qrCode }}" alt="QR Code">
            </div>
        </div>

        @if($pdfPath)
    <div class="mt-4">
        <a href="{{ Storage::url($pdfPath) }}" class="pdf-button" download>Download PDF</a>
    </div>
@endif

        <div class="mt-20">
            <a href="{{ route('fanshop.index') }}" class="back-button">Back to Fanshop</a>
        </div>
    </div>
</body>
</html>
