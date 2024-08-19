@php
    $backgroundImage = \App\Models\BackgroundImage::where('assigned_page', 'welcome')->first();
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome | {{ $clubName }}</title>

    <!-- Favicon -->
    @if($logoPath)
        <link rel="icon" href="{{ $logoPath }}" type="image/png">
    @endif

    <!-- CSS Bootstrap pour les Modals -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- CSS App -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @vite('resources/css/app.css')

    <style>
            @import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap');
        /* Background container that takes up 60% of the viewport height */
        .background-container {
            position: relative;
            width: 100%;
            min-height: 60vh; /* Adjusted height to leave space for the cover container */
            @if($backgroundImage)
                background: url('{{ asset('storage/' . $backgroundImage->image_path) }}') no-repeat center center;
                background-size: cover;
            @else
                background-color: #f8f9fa; /* Default background color if no image is available */
            @endif
        }

        /* Cover container that sits under the background */
        .cover-container {
            position: relative;
            width: 100%;
            min-height: 40vh; /* Takes the remaining height */
            background-color: #f1f1f1; /* Light gray color */
            z-index: 1; /* Ensures it sits below the info-container */
        }

        /* White container positioned at the center */
        .info-container {
            background-color: white;
            width: 40%; /* Adjusted width */
            padding: 20px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            border-radius: 8px;
            position: absolute;
            top: 60%; /* Positions the container 60% down the viewport */
            left: 50%; /* Centers the container horizontally */
            transform: translate(-50%, -50%); /* Centers the container horizontally and vertically */
            z-index: 1100; /* Ensures it stays on top of the cover-container */
        }

        .team-logo {
            height: 80px;
            width: auto;
        }

        .vs-text {
            font-size: 2rem;
            font-weight: bold;
            margin: 0 15px;
        }

        .match-info {
            text-align: center;
            font-size: 1.2rem;
            margin-top: 10px;
        }

        .match-location {
            font-size: 1rem;
            color: #555;
        }

        .match-date {
            margin-top: 10px;
            font-size: 1.5rem;
            font-weight: bold;
        }

        .title {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 20px;
            color: {{ $primaryColor }};
        }
        .flash-message-container {
            font-family: 'Bebas Neue', sans-serif;
            font-size:30px;
    position: relative;
    overflow: hidden;
    width: 100%;
    background-color: {{ $secondaryColor }};
    color: white;
    padding: 20px 0;
    z-index: 900;
    display: flex;
    align-items: center;
}

.flash-message {
    display: inline-block;
    white-space: nowrap;
    position: absolute;
    left: 100%; /* Commence en dehors de l'écran, à droite */
    animation: marquee 10s linear infinite;
}

@keyframes marquee {
    0% {
        transform: translateX(0); /* Commence à droite, en dehors de l'écran */
    }
    100% {
        transform: translateX(calc(-100% - 100vw)); /* Traverse la largeur du texte + la largeur de l'écran */
    }
}
        .weather-info {
            background-color: {{ $secondaryColor }};
            padding: 10px;
            font-weight: bold;
            z-index: 1001;
        }

        .edit-button {
            background-color: white;
            color: {{ $secondaryColor }};
            font-weight: bold;
            border: none;
            margin-left: 10px;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }

        .edit-button:hover {
            background-color: {{ $secondaryColor }};
            color: white;
        }
    </style>
</head>
<body class="bg-gray-100">
    <!-- Navbar -->
    <x-navbar />

    <div class="flash-message-container">
    <div class="weather-info">
        {{ $city }}: {{ $weatherData['main']['temp'] ?? 'N/A' }}°C
        @auth
            <button type="button" class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#editFlashMessageModal">
                EDIT
            </button>
        @endauth
    </div>
    <div class="flash-message">
        {{ $flashMessage->message ?? 'Bienvenue sur notre site web !' }}
    </div>
</div>

<!-- Modal pour éditer le flash message -->
<div class="modal fade" id="editFlashMessageModal" tabindex="-1" aria-labelledby="editFlashMessageModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('flashmessage.update') }}" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editFlashMessageModalLabel">Edit Flash Message</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="flash_message" class="form-label">Message</label>
                        <input type="text" class="form-control" id="flash_message" name="flash_message" value="{{ $flashMessage->message ?? '' }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </form>
    </div>
</div>

    <!-- Background Container with Match Info -->
    <div class="background-container">
        <!-- Titre publicitaire en grandes lettres aligné à gauche -->
        <div style="position: absolute; top: 20%; left: 5%; color: {{ $secondaryColor }}; font-family: 'Bebas Neue', sans-serif; font-size: 6rem; font-weight: bold; text-align: left; text-shadow: 2px 2px 5px rgba(0,0,0,0.7);">
            DINA KÉNITRA FC<br>
            RISE UP & DOMINATE<br>
            THE GAME!
        </div>

        <div class="info-container mt-60">
            <div class="title">NEXT GAME</div>
            @if($nextGame)
            <div class="match-info">
                <!-- Affichage du lieu du match -->
                @if(Str::startsWith($nextGame->homeTeam->name, $clubPrefix))
                    <div class="match-location">{{ $clubLocation }}, {{ $city }}</div>
                @else
                    <div class="match-location">Away</div>
                @endif

                <div class="d-flex align-items-center justify-content-center mt-4">
                    <img src="{{ asset('storage/' . $nextGame->homeTeam->logo_path) }}" alt="Home Team Logo" class="team-logo">
                    <div class="vs-text">VS</div>
                    <img src="{{ asset('storage/' . $nextGame->awayTeam->logo_path) }}" alt="Away Team Logo" class="team-logo">
                </div>
                <div class="d-flex align-items-center justify-content-center mt-3">
                    <span>{{ $nextGame->homeTeam->name }}</span>
                    <span class="vs-text">-</span>
                    <span>{{ $nextGame->awayTeam->name }}</span>
                </div>
                <div class="match-date">{{ \Carbon\Carbon::parse($nextGame->match_date)->format('d-m-Y') }}</div>
            </div>
            @else
                <div class="no-match">
                    <p>No upcoming games scheduled.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Cover Container -->
    <div class="cover-container"></div>

    <!-- Footer -->
    <x-footer />

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
