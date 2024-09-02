<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.payment_successful') }} | {{ $clubName }}</title>
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
            max-width: 150px;
            height: auto;
        }

        .club-name {
            font-size: 1.5rem;
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

        .back-button, .pdf-button {
            background-color: {{ $primaryColor }};
            color: white;
            font-weight: bold;
            padding: 10px 20px;
            border-radius: 8px;
            transition: background-color 0.3s;
            text-decoration: none;
            display: inline-block;
            margin-top: 10px;
        }

        .back-button:hover, .pdf-button:hover {
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
        animation: blink 1.5s infinite;
    }

    .container {
        padding: 15px;
    }
    </style>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto py-12 text-center">
        @if($logoPath)
        <div class="logo-container">
            <img src="{{ asset($logoPath) }}" alt="Club Logo">
            <div class="club-name">{{ $clubName }}</div>
        </div>
        <hr>
        @endif

        <h1 class="text-3xl font-bold mb-6 text-green-500">{{ __('messages.payment_successful') }}</h1>

        <div class="bg-white p-6 rounded-lg shadow-lg inline-block w-full max-w-lg">
            <h2 class="text-2xl font-semibold mb-4">{{ __('messages.reservation_details') }}</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                    <tbody>
                        <tr class="border-b">
                            <td class="py-3 px-6 text-left font-semibold">{{ __('messages.email') }}:</td>
                            <td class="py-3 px-6">{{ $reservationDetails['email'] }}</td>
                        </tr>
                        <tr class="border-b bg-gray-50">
                            <td class="py-3 px-6 text-left font-semibold">{{ __('messages.amount_paid') }}:</td>
                            <td class="py-3 px-6">{{ number_format($reservationDetails['amount'], 2) }} {{ $reservationDetails['currency'] }}</td>
                        </tr>
                        <tr class="border-b">
                            <td class="py-3 px-6 text-left font-semibold">{{ __('messages.date') }}:</td>
                            <td class="py-3 px-6">{{ $reservationDetails['date'] }}</td>
                        </tr>
                        <tr class="border-b bg-gray-50">
                            <td class="py-3 px-6 text-left font-semibold">{{ __('messages.reservation_id') }}:</td>
                            <td class="py-3 px-6">{{ $reservationDetails['reservation_id'] }}</td>
                        </tr>
                        @if($reservationDetails['game'])
                            <tr class="border-b">
                                <td class="py-3 px-6 text-left font-semibold">{{ __('messages.match') }}:</td>
                                <td class="py-3 px-6">{{ $reservationDetails['game']->homeTeam->name }} vs {{ $reservationDetails['game']->awayTeam->name }}</td>
                            </tr>
                            <tr class="border-b bg-gray-50">
                                <td class="py-3 px-6 text-left font-semibold">{{ __('messages.date_of_match') }}:</td>
                                <td class="py-3 px-6">{{ \Carbon\Carbon::parse($reservationDetails['game']->match_date)->format('d-m-Y') }}</td>
                            </tr>
                        @endif
                        <tr class="border-b">
                            <td class="py-3 px-6 text-left font-semibold">{{ __('messages.seats_reserved') }}:</td>
                            <td class="py-3 px-6">{{ $reservationDetails['seats_reserved'] }}</td>
                        </tr>
                        <tr>
                            <td class="py-3 px-6 text-left font-semibold">{{ __('messages.ticket_type') }}:</td>
                            <td class="py-3 px-6">{{ $reservationDetails['tribune_name'] }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="qr-code-container">
            <div class="qr-code-wrapper">
                <h3 class="text-xl font-semibold mb-2">{{ __('messages.scan_qr') }}</h3>
                <img src="data:image/svg+xml;base64,{{ $qrCode }}" alt="QR Code" class="max-w-xs w-full h-auto">
            </div>
        </div>

        @if($pdfPath)
            <div class="mt-4">
                <a href="{{ Storage::url($pdfPath) }}" class="pdf-button" download>{{ __('messages.download_pdf') }}</a>
            </div>
        @endif

        <div class="mt-20 mb-20">
            <a href="{{ route('fanshop.index') }}" class="back-button">{{ __('messages.back_to_fanshop') }}</a>
        </div>
    </div>
</body>
</html>
