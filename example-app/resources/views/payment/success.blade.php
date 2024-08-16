<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Success</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100">
    <div class="container mx-auto py-12 text-center">
        <h1 class="text-3xl font-bold mb-6 text-green-500">Payment Successful!</h1>

        <div class="bg-white p-6 rounded-lg shadow-lg inline-block">
            <h2 class="text-2xl font-semibold mb-4">Your Reservation Details</h2>
            <p><strong>Name:</strong> {{ $reservationDetails['name'] }}</p>
            <p><strong>Email:</strong> {{ $reservationDetails['email'] }}</p>
            <p><strong>Amount Paid:</strong> {{ number_format($reservationDetails['amount'], 2) }} {{ $reservationDetails['currency'] }}</p>
            <p><strong>Date:</strong> {{ $reservationDetails['date'] }}</p>
            <p><strong>Reservation ID:</strong> {{ $reservationDetails['reservation_id'] }}</p>
        </div>

        <div class="mt-6">
            <h3 class="text-xl font-semibold mb-2">Scan this QR code for your reservation details:</h3>
            <div>
                {!! $qrCode !!}
            </div>
        </div>

        <div class="mt-6">
            <a href="{{ route('fanshop.index') }}" class="bg-blue-500 text-white font-bold py-2 px-4 rounded transition duration-200 hover:bg-blue-700">Back to Fanshop</a>
        </div>
    </div>
</body>
</html>
