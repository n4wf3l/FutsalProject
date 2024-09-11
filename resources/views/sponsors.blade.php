<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.sponsors') }} | {{ $clubName }}</title>
    @if($logoPath)
        <link rel="icon" href="{{ $logoPath }}" type="image/png">
    @endif
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css"/>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap');

        .custom-font {
            font-family: 'Bebas Neue', sans-serif;
            letter-spacing: 2px;
        }

        .sponsor-card {
            background-color: #ffffff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.2s ease-in-out;
            margin: 10px;
            width: 30%;
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .sponsor-card:hover {
            transform: translateY(-5px);
        }

        .delete-form {
            position: absolute;
            top: 10px;
            right: 10px;
        }

        .delete-button {
            background-color: #DC2626;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 50%;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .delete-button:hover {
            background-color: #B91C1C;
        }

        .sponsor-logo {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 20px 0;
            height: 150px;
        }

        .sponsor-logo img {
            max-height: 100px;
            max-width: 100px;
        }

        .sponsor-content {
            padding: 1.5rem;
            width: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .sponsor-name {
            font-size: 1.25rem;
            font-weight: bold;
            color: #333;
            text-align: right;
        }

        .sponsor-partner {
            font-size: 1.25rem;
            color: #555;
            text-align: left;
        }

        .sponsor-details {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;
        }

        .sponsor-hr {
            border-top: 1px solid #e5e5e5;
            margin: 1rem 0;
        }

        .sponsor-website {
            color: #1D4ED8;
            font-weight: bold;
            text-decoration: none;
            display: block;
            text-align: center;
            margin-top: 1rem;
        }

        .sponsor-website:hover {
            text-decoration: underline;
        }

        .sponsors-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            gap: 10px;
        }

        .partner-section {
            background: linear-gradient(135deg, {{ $primaryColor }} 0%, {{ $secondaryColor }} 100%);
            color: white;
            text-align: center;
            padding: 80px 20px;
            position: relative;
        }

        .partner-title {
            font-size: 1.25rem;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 1rem;
        }

        .partner-subtitle {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 1.5rem;
        }

        .partner-text {
            font-size: 1.125rem;
            margin-bottom: 2rem;
        }

        .partner-button {
            background-color: white;
            color: #333;
            font-weight: bold;
            padding: 12px 24px;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s, color 0.3s;
        }

        .partner-button:hover {
            background-color: #e5e5e5;
            color: #333;
        }

        /* Responsive Styles */
        @media (max-width: 1024px) {
            .sponsor-card {
                width: 45%;
            }
        }

        @media (max-width: 768px) {
            .sponsor-card {
                width: 100%;
            }

            .sponsor-logo img {
                max-height: 80px;
                max-width: 80px;
            }

            .partner-subtitle {
                font-size: 2rem;
            }

            .partner-text {
                font-size: 1rem;
            }
        }

        @media (max-width: 480px) {
            .sponsor-card {
                width: 100%;
            }

            .sponsor-name,
            .sponsor-partner {
                font-size: 1rem;
            }

            .partner-subtitle {
                font-size: 1.5rem;
            }

            .partner-text {
                font-size: 0.875rem;
            }

            .partner-button {
                padding: 10px 20px;
                font-size: 0.875rem;
            }
        }
    </style>
</head>
<body class="bg-gray-100" @if($backgroundImage) style="background: url('{{ asset('storage/' . $backgroundImage->image_path) }}') no-repeat center center fixed; background-size: cover;" @endif>
    <x-navbar />

    <header class="text-center my-12">
        <x-page-title subtitle="{{ __('messages.sponsors_subtitle') }}">
            {{ __('messages.sponsors') }}
        </x-page-title>
    </header>

    <main class="py-12 flex flex-col items-center">
        <div class="container mx-auto px-4 text-center" data-aos="fade-right">
            @auth
            <div class="mb-6">
                <x-button 
                    route="{{ route('sponsors.create') }}"
                    buttonText="{{ __('messages.add_sponsor') }}" 
                    primaryColor="#DC2626" 
                    secondaryColor="#B91C1C"
                    class="custom-font"
                />
            </div>
            @endauth

            <div class="sponsors-container">
                @if($sponsors->isEmpty())
                    <div class="text-center text-gray-600">
                        {{ __('messages.no_sponsors') }}
                    </div>
                @else
                    @foreach($sponsors as $sponsor)
                    <div class="sponsor-card">
                        @auth
                        <form action="{{ route('sponsors.destroy', $sponsor->id) }}" method="POST" class="delete-form" onsubmit="return confirm('{{ __('messages.delete_confirmation_sponsor') }}');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="delete-button">X</button>
                        </form>
                        @endauth

                        @if($sponsor->logo)
                        <div class="sponsor-logo">
                            <img src="{{ asset('storage/' . $sponsor->logo) }}" alt="{{ $sponsor->name }}">
                        </div>
                        @endif
                        <div class="sponsor-content">
                            <div class="sponsor-details">
                                <span class="sponsor-partner custom-font">{{ __('messages.partner') }}:</span>
                                <span class="sponsor-name custom-font">{{ $sponsor->name }}</span>
                            </div>
                            <div class="sponsor-hr"></div>
                            @if($sponsor->website)
                            <a href="{{ $sponsor->website }}" class="sponsor-website custom-font" style="font-size:20px; color: {{ $primaryColor }};" target="_blank">{{ __('messages.visit_website') }}</a>
                            @endif
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>
        </div>
    </main>
    <div class="partner-section">
        <div class="partner-content">
            <h2 class="partner-title custom-font" data-aos="fade-right">{{ __('messages.partners_and_sponsors') }}</h2>
            <h3 class="partner-subtitle custom-font" data-aos="fade-right">{{ __('messages.become_partner') }}</h3>
            <p class="partner-text" data-aos="fade-right">{{ __('messages.become_partner_text') }}</p>
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
