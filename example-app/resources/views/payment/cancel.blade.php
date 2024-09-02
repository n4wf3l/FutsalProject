<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.payment_canceled') }} | {{ $clubName }}</title>
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

        .back-button {
            background-color: {{ $primaryColor }};
            color: white;
            font-weight: bold;
            padding: 10px 20px;
            border-radius: 8px;
            transition: background-color 0.3s, transform 0.2s;
            display: inline-block;
            margin-top: 10px;
            text-decoration: none;
        }

        .back-button:hover {
            background-color: {{ $secondaryColor }};
            transform: translateY(-2px);
        }

        .container {
            padding: 15px;
        }

        .message-container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            margin: 0 auto;
        }

        .message-container p {
            font-size: 1.125rem;
            color: #555;
            margin-bottom: 20px;
        }

        .title {
            font-size: 1.875rem;
            font-weight: bold;
            color: #e53e3e;
            margin-bottom: 20px;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto py-12 text-center">
        @if($logoPath)
        <div class="logo-container">
            <img src="{{ asset($logoPath) }}" alt="{{ __('messages.club_logo_alt') }}">
            <div class="club-name">{{ $clubName }}</div>
        </div>
        <hr>
        @endif

        <div class="message-container">
            <h1 class="title">{{ __('messages.payment_canceled') }}</h1>
            <p>{{ __('messages.payment_canceled_message') }}</p>
            <a href="{{ route('fanshop.index') }}" class="back-button">{{ __('messages.return_to_fanshop') }}</a>
        </div>
    </div>
</body>
</html>
