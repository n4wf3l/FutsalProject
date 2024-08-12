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
            padding: 20px;
            font-size: 60px; /* Taille du numéro augmentée */
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
        .club-logo {
            position: absolute;
            top: 10px;
            left: 10px;
            width: 10%; /* Ajuster la taille du logo à 10% de la largeur de l'image du joueur */
            height: auto;
            z-index: 20;
        }
    </style>
</head>
<body class="bg-gray-100">
    <x-navbar />
    <header class="text-center my-12" style="margin-top: 20px; font-size:60px;">
        <h1 class="text-6xl font-bold text-gray-900">Players</h1>
        <div class="flex justify-center items-center mt-4">
            <p class="text-xl text-gray-600">Discover additional information by hovering with your mouse.</p>
        </div>
    </header>

    @php
    $userSettings = Auth::check() ? Auth::user()->userSettings : null;
    @endphp

    @if($players->isEmpty())
        <p class="text-gray-600 text-center">There are no players in the database.</p>
    @else
        <div class="player-container grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
            @foreach($players as $player)
            <div class="player-item relative bg-white shadow-lg rounded-lg overflow-hidden group">
                <!-- Club Logo -->
                @if($userSettings && $userSettings->logo)
                    <img src="{{ asset('storage/' . $userSettings->logo) }}" alt="Club Logo" class="club-logo" style="height: 60px; width: auto;">
                @endif
                    <!-- Player Image -->
                    @if($player->photo)
                        <img src="{{ asset('storage/' . $player->photo) }}" alt="{{ $player->first_name }} {{ $player->last_name }}" class="w-full h-48 object-cover">
                        <!-- Player Number -->
                        <div class="player-number absolute top-2 right-2 bg-black text-white text-lg font-bold rounded-full px-3 py-1 z-10">{{ $player->number }}</div>
                        <!-- Player Name on Image -->
                        <div class="player-info absolute bottom-0 left-0 right-0 bg-black bg-opacity-75 text-white p-2 z-10">
                            <span>{{ $player->first_name }} {{ $player->last_name }}</span>
                        </div>
                    @else
                        <div class="bg-gray-300 flex items-center justify-center h-48">
                            <span class="text-gray-700">No Photo</span>
                        </div>
                    @endif

                    <!-- Player Info Overlay -->
                    <div class="player-overlay p-4">
                        <p><strong>Position:</strong> {{ $player->position }}</p>
                        <p><strong>Nationality:</strong> {{ $player->nationality }}</p>
                        <p><strong>Height:</strong> {{ $player->height }} cm</p>
                        <p><strong>Contract Until:</strong> {{ \Carbon\Carbon::parse($player->contract_until)->format('d-m-Y') }}</p>
                        
                        <!-- Delete button, visible only to authenticated users -->
                        @auth
<div class="flex mt-4">
    <!-- Delete Button -->
    <form action="{{ route('players.destroy', $player->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this player?');">
        @csrf
        @method('DELETE')
        <button type="submit" style="background-color: #DC2626; color: white; padding: 8px 16px; border-radius: 8px; margin-right: 10px; text-align: center;">
            Delete
        </button>
    </form>

    <!-- Edit Button -->
    <a href="{{ route('players.edit', $player->id) }}" 
       style="background-color: #2563EB; color: white; padding: 8px 16px; border-radius: 8px; display: inline-block; text-align: center; text-decoration: none;"
       onmouseover="this.style.backgroundColor='#1D4ED8';" 
       onmouseout="this.style.backgroundColor='#2563EB';">
        Edit
    </a>
</div>
@endauth
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>


<section class="text-center my-12">
    <h1 class="text-6xl font-bold text-gray-900">Technisch- & Medische Staff</h1>
    <div class="flex justify-center items-center mt-4 mb-12">
        <p class="text-xl text-gray-600">Discover additional information by hovering with your mouse.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-7xl mx-auto">
        @foreach($staff as $member)
        <div class="bg-dark-blue p-6 rounded-lg shadow-lg">
            <h3 class="text-xl font-bold text-white">{{ $member->first_name }} {{ $member->last_name }}</h3>
            <p class="text-gray-400">{{ $member->position }}</p>

            @auth
            <div class="mt-4 flex justify-between">
                <a href="{{ route('staff.edit', $member->id) }}" 
                   class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-700 focus:outline-none ml-2">
                    Edit
                </a>
                <form action="{{ route('staff.destroy', $member->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this staff member?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 text-white py-2 px-4 rounded hover:bg-red-700">Delete</button>
                </form>
            </div>
            @endauth
        </div>
        @endforeach
    </div>
</section>

<style>
    /* Ajoutez ces styles personnalisés pour correspondre au design */
    .bg-dark-blue {
        background-color: #0C2033; /* Couleur de fond sombre similaire à celle de l'exemple */
    }
</style>

</body>
</html>
