<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.legal') }} | {{ $clubName }}</title>
    @if($logoPath)
        <link rel="icon" href="{{ $logoPath }}" type="image/png">
    @endif
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f9f9f9;
            color: #333;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .legal-container {
            max-width: 1000px;
            margin: 50px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1, h2 {
            color: {{ $primaryColor }};
            margin-bottom: 20px;
        }

        p {
            margin-bottom: 20px;
            line-height: 1.6;
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: bold;
            margin-top: 30px;
        }

        /* Style for centering the button */
        .button-container {
            text-align: center;
            margin: 40px 0;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .legal-container {
                margin: 20px;
                padding: 15px;
            }

            .section-title {
                font-size: 1.25rem;
                margin-top: 20px;
            }

            p {
                font-size: 0.9rem;
                line-height: 1.5;
            }
        }

        @media (max-width: 480px) {
            .legal-container {
                margin: 10px;
                padding: 10px;
            }

            .section-title {
                font-size: 1.1rem;
                margin-top: 15px;
            }

            p {
                font-size: 0.85rem;
                line-height: 1.4;
            }

            .button-container {
                margin: 30px 0;
            }
        }
    </style>
</head>
<body>
    <x-navbar />

    <div class="legal-container">
        <h1>{{ __('messages.legal_notices') }}</h1>
        
        <section id="privacy-policy">
            <h2 class="section-title">{{ __('messages.privacy_policy') }}</h2>
            <p>{!! __('messages.privacy_policy_text', ['clubName' => $clubName, 'email' => $email]) !!}</p>
        </section>

        <section id="terms-conditions">
            <h2 class="section-title">{{ __('messages.terms_conditions') }}</h2>
            <p>{!! __('messages.terms_conditions_text', ['clubName' => $clubName, 'email' => $email]) !!}</p>
        </section>

        <section id="cookies-policy">
            <h2 class="section-title">{{ __('messages.cookies_policy') }}</h2>
            <p>{!! __('messages.cookies_policy_text', ['clubName' => $clubName]) !!}</p>
        </section>

        <!-- Centered button with margin -->
        <div class="button-container">
            <x-button 
                route="{{ route('contact.show') }}"
                buttonText="{{ __('messages.contact_us') }}" 
                primaryColor="#B91C1C" 
                secondaryColor="#DC2626"
                class="custom-font"
            />
        </div>
    </div>

    <x-footer />
</body>
</html>
