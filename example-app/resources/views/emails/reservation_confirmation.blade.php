<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation Confirmation</title>
</head>
<body>
    <h1>Reservation Confirmation</h1>
    <p>Dear,</p>
    <p>Your reservation has been successfully completed. Below are your reservation details:</p>

    <table border="1" cellpadding="10" cellspacing="0">
        <tr>
            <th>Email</th>
            <td>{{ $reservationDetails['email'] }}</td>
        </tr>
        <tr>
            <th>Amount Paid</th>
            <td>{{ number_format($reservationDetails['amount'], 2) }} {{ $reservationDetails['currency'] }}</td>
        </tr>
        <tr>
            <th>Date</th>
            <td>{{ $reservationDetails['date'] }}</td>
        </tr>
        <tr>
            <th>Reservation ID</th>
            <td>{{ $reservationDetails['reservation_id'] }}</td>
        </tr>
        @if($reservationDetails['game'])
        <tr>
            <th>Match</th>
            <td>{{ $reservationDetails['game']->homeTeam->name }} vs {{ $reservationDetails['game']->awayTeam->name }}</td>
        </tr>
        <tr>
            <th>Date of Match</th>
            <td>{{ \Carbon\Carbon::parse($reservationDetails['game']->match_date)->format('d-m-Y') }}</td>
        </tr>
        @endif
        <tr>
            <th>Seats Reserved</th>
            <td>{{ $reservationDetails['seats_reserved'] }}</td>
        </tr>
    </table>

    <p>Scan the QR code below for your reservation details:</p>
    <img src="data:image/svg+xml;base64,{{ $qrCode }}" alt="QR Code">

    <p>Thank you for your purchase!</p>
</body>
</html>
