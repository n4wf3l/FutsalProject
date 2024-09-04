<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.contact') }} | {{ $clubName }}</title>
    @if($logoPath)
        <link rel="icon" href="{{ $logoPath }}" type="image/png"> <!-- Type de l'image selon le type du logo -->
    @endif
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
          integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- Meta Tags for SEO -->
<meta name="description" content="{{ __('messages.contact') }} - Get in touch with {{ $clubName }} in {{ $clubLocation }}. Contact us for any inquiries, feedback, or support.">
<meta name="keywords" content="futsal, {{ $clubName }}, {{ $clubLocation }}, contact, sports, inquiries"> 
<meta property="og:title" content="{{ __('messages.contact') }} - {{ $clubName }} in {{ $clubLocation }}">
<meta property="og:description" content="{{ __('messages.contact') }} - Reach out to {{ $clubName }} for support or information.">
<meta property="og:url" content="{{ url()->current() }}">
<meta name="robots" content="index, follow">
<link rel="canonical" href="{{ url()->current() }}">
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    <style>
        .contact-container {
            text-align: left;
            display: flex;
            flex-direction: column;
            max-width: 1200px;
            margin: 50px auto;
            padding: 2rem;
            background-color: #f9f9f9;
            border-radius: 0.5rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            position: relative;
            z-index: 2;
        }

        .contact-info, .contact-form {
            flex: 1;
            margin-bottom: 2rem;
        }

        .contact-info h2 {
            font-size: 1.5rem;
            font-weight: bold;
            color: {{ $primaryColor }};
            text-transform: uppercase;
            margin-bottom: 1rem;
        }

        .contact-info h1 {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 1rem;
            color: #333;
        }

        .contact-info p {
            font-size: 1.1rem;
            color: #555;
            margin-bottom: 2rem;
        }

        .info-item {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .info-item i {
            margin-right: 1rem;
            font-size: 1.5rem;
            color: {{ $primaryColor }};
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            font-weight: bold;
            color: {{ $primaryColor }};
            text-transform: uppercase;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ccc;
            border-radius: 0.5rem;
            margin-top: 0.5rem;
        }

        .form-group textarea {
            resize: none;
            height: 150px;
        }

        .submit-button {
            width: 100%;
            padding: 1rem;
            border-radius: 0.5rem;
            text-align: center;
            font-weight: bold;
            cursor: pointer;
            background-color: {{ $primaryColor }};
            color: white;
            transition: background-color 0.3s ease;
            text-transform: uppercase;
        }

        .submit-button:hover {
            background-color: {{ $secondaryColor }};
        }

        footer {
            position: relative;
            z-index: 1;
        }

        /* Responsive styles */
        @media (min-width: 768px) {
            .contact-container {
                flex-direction: row;
            }

            .contact-info, .contact-form {
                margin-right: 2rem;
                margin-bottom: 0;
            }
        }

        @media (max-width: 767px) {
            .contact-info h1 {
                font-size: 2rem;
            }

            .contact-info p {
                font-size: 1rem;
            }

            .info-item i {
                font-size: 1.25rem;
            }
        }
    </style>
</head>
<body class="bg-gray-100" @if($backgroundImage) style="background: url('{{ asset('storage/' . $backgroundImage->image_path) }}') no-repeat center center fixed; background-size: cover;" @endif>
    <x-navbar />

    <header class="text-center my-12">
        <x-page-title subtitle="{{ __('messages.contact_subtitle') }}">
            {{ __('messages.contact') }}
        </x-page-title>
    </header>

    <div class="contact-container">
        <div class="contact-info" data-aos="fade-right">
            <h2>{{ __('messages.contact_us') }}</h2>
            <h1>{{ __('messages.get_in_touch') }}</h1>
            <p>{{ __('messages.contact_form_instruction') }}</p>

            <div class="info-item">
                <i class="fas fa-map-marker-alt"></i>
                <div>
                    <strong>{{ __('messages.address') }}</strong>
                    <p>{{ $clubLocation }}</p>
                </div>
            </div>

            <div class="info-item">
                <i class="fas fa-phone-alt"></i>
                <div>
                    <strong>{{ __('messages.phone_number') }}</strong>
                    <p>{{ __('messages.gc') }}: {{ $phone }}</p>
                </div>
            </div>

            <div class="info-item">
                <i class="fas fa-envelope"></i>
                <div>
                    <strong>{{ __('messages.email_address') }}</strong>
                    <p>{{ $email }}</p>
                </div>
            </div>
        </div>

        <div class="contact-form" data-aos="fade-left">
            <form action="{{ route('contact.send') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="firstname">{{ __('messages.first_name') }} *</label>
                    <input type="text" name="firstname" id="firstname" required>
                </div>
                <div class="form-group">
                    <label for="lastname">{{ __('messages.last_name') }} *</label>
                    <input type="text" name="lastname" id="lastname" required>
                </div>
                <div class="form-group">
                    <label for="email">{{ __('messages.email_address') }} *</label>
                    <input type="email" name="email" id="email" required>
                </div>
                <div class="form-group">
                    <label for="phone">{{ __('messages.phone_number') }} *</label>
                    <input type="tel" name="phone" id="phone" required>
                </div>
                <div class="form-group">
                    <label for="message">{{ __('messages.message_or_questions') }} *</label>
                    <textarea name="message" id="message" required></textarea>
                </div>
                <button type="submit" class="submit-button">{{ __('messages.send') }}</button>
            </form>
        </div>
    </div>

    <x-footer />
</body>
</html>
