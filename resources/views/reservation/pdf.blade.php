<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.reservation_details') }} | {{ $clubName }}</title>
    @if($logoPath)
        <link rel="icon" href="{{ $logoPath }}" type="image/png">
    @endif
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
        }

        .header h1 {
            font-size: 32px;
            margin-bottom: 10px;
        }

        .header h2 {
            font-size: 24px;
            color: #555;
            margin-top: 0;
        }

        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
        }

        .details-table th, .details-table td {
            border: 1px solid #ddd;
            padding: 12px 15px;
            text-align: left;
        }

        .details-table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .qr-code-container {
            text-align: center;
            margin-top: 30px;
        }

        .qr-code-container h3 {
            margin-bottom: 20px;
            font-size: 20px;
        }

        .note {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #777;
        }

        .qr-code img {
            max-width: 200px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $clubName }}</h1>
        <h2>{{ __('messages.reservation_details') }}</h2>
    </div>

    <table class="details-table">
        <tr>
            <th>{{ __('messages.email') }}</th>
            <td>{{ $reservationDetails['email'] }}</td>
        </tr>
        <tr>
            <th>{{ __('messages.amount_paid') }}</th>
            <td>{{ number_format($reservationDetails['amount'], 2) }} {{ $reservationDetails['currency'] }}</td>
        </tr>
        <tr>
            <th>{{ __('messages.date_of_purchase') }}</th>
            <td>{{ $reservationDetails['date'] }}</td>
        </tr>
        <tr>
            <th>{{ __('messages.reservation_id') }}</th>
            <td>{{ $reservationDetails['reservation_id'] }}</td>
        </tr>
        @if($reservationDetails['game'])
        <tr>
        <th>{{ __('messages.next_match') }}</th>
            <td>{{ $reservationDetails['game']->homeTeam->name }} vs {{ $reservationDetails['game']->awayTeam->name }}</td>
        </tr>
        <tr>
            <th>{{ __('messages.date_of_match') }}</th>
            <td>{{ \Carbon\Carbon::parse($reservationDetails['game']->match_date)->format('d-m-Y') }}</td>
        </tr>
        @endif
        <tr>
            <th>{{ __('messages.seats_reserved') }}</th>
            <td>{{ $reservationDetails['seats_reserved'] }}</td>
        </tr>
        <tr>
            <th>{{ __('messages.ticket_type') }}</th>
            <td>{{ $reservationDetails['tribune_name'] }}</td>
        </tr>
    </table>

    <div class="qr-code-container">
        <h3>{{ __('messages.scan_qr') }}</h3>
        <div class="qr-code">
            <img src="data:image/svg+xml;base64,{{ $qrCode }}" alt="QR Code">
        </div>
    </div>

    <div class="note">
        <p>{{ __('messages.qr_note') }}</p>
    </div>
</body>
</html>
