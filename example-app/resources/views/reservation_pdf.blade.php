<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h1 {
            color: #333;
        }
        p {
            font-size: 18px;
        }
        .details {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <h1>Reservation Confirmation</h1>
    <p>Thank you for your reservation. Below are the details:</p>

    <div class="details">
        <p><strong>Name:</strong> {{ $reservationDetails['name'] }}</p>
        <p><strong>Email:</strong> {{ $reservationDetails['email'] }}</p>
        <p><strong>Amount Paid:</strong> {{ number_format($reservationDetails['amount'], 2) }} {{ $reservationDetails['currency'] }}</p>
        <p><strong>Date:</strong> {{ $reservationDetails['date'] }}</p>
        <p><strong>Reservation ID:</strong> {{ $reservationDetails['reservation_id'] }}</p>
        <p><strong>Status:</strong> Valid Ticket</p>
    </div>
</body>
</html>
