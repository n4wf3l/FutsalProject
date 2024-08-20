<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sponsors | {{ $clubName }}</title>
    @if($logoPath)
        <link rel="icon" href="{{ $logoPath }}" type="image/png"> <!-- Type de l'image selon le type du logo -->
    @endif
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @vite('resources/css/app.css')
    <style>
        .sponsor-card {
            background-color: #ffffff;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.2s ease-in-out;
            margin: 16px; /* Add margin between cards */
        }
        
        .sponsor-card:hover {
            transform: translateY(-5px);
        }

        .sponsor-logo img {
            max-height: 100px;
            margin: 0 auto;
            display: block;
        }

        .sponsor-content {
            padding: 1rem;
            text-align: center;
        }

        .sponsor-name {
            font-size: 1.25rem;
            font-weight: bold;
            color: #333;
        }

        .sponsor-partner {
            color: #555;
            margin-top: 0.5rem;
            font-size: 1rem;
        }

        .sponsor-website {
            margin-top: 1rem;
            color: #1D4ED8;
            font-weight: bold;
            text-decoration: none;
            display: inline-block;
        }

        .sponsor-website:hover {
            text-decoration: underline;
        }

        /* Flexbox container for cards with spacing */
        .sponsors-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 16px; /* Add gap between the cards */
        }

        .no-sponsors-message {
            font-size: 1.5rem;
            color: #555;
            margin-top: 20px;
        }
    </style>
</head>
<body class="bg-gray-100">
    <x-navbar />

    <header class="text-center my-12" style="margin-top: 20px; font-size:60px;">
        <h1 class="text-6xl font-bold text-gray-900">Sponsors</h1>
        <div class="flex justify-center items-center mt-4">
            <p class="text-xl text-gray-600">Discover additional information by hovering with your mouse.</p>
        </div>
    </header>

    <main class="py-12 flex flex-col items-center">
        <div class="container mx-auto px-4 text-center">

            @if(session('success'))
                <div class="bg-green-500 text-white p-4 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @auth
            <div class="mb-6">
                <a href="{{ route('sponsors.create') }}"
                   class="text-white font-bold py-2 px-6 rounded-full transition duration-200 shadow-lg text-center"
                   style="background-color: {{ $primaryColor }};"
                   onmouseover="this.style.backgroundColor='{{ $secondaryColor }}'"
                   onmouseout="this.style.backgroundColor='{{ $primaryColor }}'">
                    Add Sponsor
                </a>
            </div>
            @endauth

            <div class="sponsors-container">
                @if($sponsors->isEmpty())
                    <div class="no-sponsors-message">
                        There are no sponsors at the moment.
                    </div>
                @else
                    @foreach($sponsors as $sponsor)
                        <div class="sponsor-card">
                            @if($sponsor->logo)
                                <div class="sponsor-logo p-4">
                                    <img src="{{ asset('storage/' . $sponsor->logo) }}" alt="{{ $sponsor->name }}">
                                </div>
                            @endif
                            <div class="sponsor-content">
                                <h2 class="sponsor-name">{{ $sponsor->name }}</h2>
                                @if($sponsor->website)
                                    <a href="{{ $sponsor->website }}" class="sponsor-website" target="_blank">Visit the website →</a>
                                @endif
                                <!-- Add Delete Button for authenticated users only -->
                                @auth
                                <form action="{{ route('sponsors.destroy', $sponsor->id) }}" method="POST" class="mt-4" onsubmit="return confirm('Are you sure you want to delete this sponsor?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="text-white font-bold py-2 px-6 rounded-full transition duration-200 shadow-lg text-center"
                                            style="background-color: #DC2626; color: white; padding: 8px 16px; border-radius: 8px; text-align: center;">
                                        Delete Sponsor
                                    </button>
                                </form>
                                @endauth
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </main>

    <x-footer />
</body>
</html>