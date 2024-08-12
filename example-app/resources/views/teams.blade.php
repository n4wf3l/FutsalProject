<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teams</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @vite('resources/css/app.css')
    <style>
        .player-container {
            display: grid;
            grid-template-columns: repeat(4, 1fr); /* Grille de 4 colonnes */
            gap: 20px; /* Espacement entre les conteneurs */
            justify-items: center; /* Centrer les conteneurs horizontalement */
            margin: 0 auto;
            max-width: 1600px; /* Limite la largeur totale à 1600px pour la grille */
            padding: 20px;
        }
        .player-item {
            width: 100%; /* Le conteneur prend toute la largeur disponible dans la colonne */
            height: 500px; /* Augmenter la hauteur pour obtenir un rectangle */
            position: relative;
            overflow: hidden;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        .player-item img {
            width: 100%;
            height: 100%;
            object-fit: cover; /* Remplit entièrement le conteneur sans déformer l'image */
            display: block;
        }
        .player-info {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            text-align: center;
            padding: 10px;
            font-weight: bold;
            font-size: 35px; 
        }
        .player-number {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 10px;
            font-size: 24px; /* Taille du numéro augmentée */
            font-weight: bold;
            border-radius: 50%;
        }
        .player-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.75);
            color: white;
            opacity: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            transition: opacity 0.3s ease;
        }
        .player-item:hover .player-overlay {
            opacity: 1;
        }
    </style>
</head>
<body class="bg-gray-100">
    <x-navbar />

    <div class="container mx-auto mt-8">
        <h1 class="text-3xl font-bold mb-8 text-gray-900 text-center">Our Team</h1>

        @if($players->isEmpty())
            <p class="text-gray-600 text-center">There are no players in the database.</p>
        @else
            <div class="player-container">
                @foreach($players as $player)
                    <div class="player-item">
                        <!-- Player Image -->
                        @if($player->photo)
                            <img src="{{ asset('storage/' . $player->photo) }}" alt="{{ $player->first_name }} {{ $player->last_name }}">
                            <!-- Player Number -->
                            <div class="player-number">{{ $player->number }}</div>
                            <!-- Player Name on Image -->
                            <div class="player-info">
                                <span>{{ $player->first_name }} {{ $player->last_name }}</span>
                            </div>
                        @else
                            <div class="bg-gray-300 flex items-center justify-center h-full">
                                <span class="text-gray-700">No Photo</span>
                            </div>
                        @endif

                        <!-- Player Info Overlay (shown on hover) -->
                        <div class="player-overlay">
                            <p><strong>Position:</strong> {{ $player->position }}</p>
                            <p><strong>Nationality:</strong> {{ $player->nationality }}</p>
                            <p><strong>Height:</strong> {{ $player->height }} cm</p>
                            <p><strong>Contract Until:</strong> {{ \Carbon\Carbon::parse($player->contract_until)->format('d-m-Y') }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</body>
</html>
