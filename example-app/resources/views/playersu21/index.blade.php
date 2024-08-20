@php
use Illuminate\Support\Facades\Auth;
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>U21 Team | {{ $clubName }}</title>
    @if($logoPath)
        <link rel="icon" href="{{ $logoPath }}" type="image/png"> <!-- Type de l'image selon le type du logo -->
    @endif
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        /* Styles généraux pour le conteneur des joueurs */
        .player-container {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            justify-items: center;
            margin: 0 auto;
            margin-bottom: 50px;
            max-width: 1600px;
            padding: 20px;
            background-color: white;
        }

        .player-item {
            width: 100%;
            height: 500px;
            position: relative;
            overflow: hidden;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .player-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
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
    background-color: rgba(0, 0, 0, 0.5); /* Réduit l'opacité à 50% */
    color: white;
    width: 60px; /* Réduit la largeur */
    height: 60px; /* Réduit la hauteur */
    font-size: 40px; /* Ajuste la taille de la police */
    font-weight: bold;
    border-radius: 50%; /* Assure un cercle parfait */
    display: flex; /* Centre le contenu */
    align-items: center; /* Centre verticalement le contenu */
    justify-content: center; /* Centre horizontalement le contenu */
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
            width: 10%;
            height: auto;
            z-index: 20;
        }
    </style>
</head>
<body class="bg-gray-100">
    <x-navbar />

    <header class="text-center my-12">
    <x-page-title subtitle="🌟 Explore the rising stars of our U21 team, showcasing the next generation of talent ready to make their mark on the field. The future of football starts here! ">
    U21 Squad {{ $championship->season }}
</x-page-title>

@auth
        <a href="{{ route('playersu21.create') }}" class="text-white font-bold py-2 px-6 rounded-full transition duration-200 shadow-lg text-center button-hover-primary"  style="font-size:20px; background-color: {{ $primaryColor }};"
                   onmouseover="this.style.backgroundColor='{{ $secondaryColor }}'"
                   onmouseout="this.style.backgroundColor='{{ $primaryColor }}'">
                Add U21 Player
            </a>
        @endauth
    </header>

    @php
    $userSettings = Auth::check() ? Auth::user()->userSettings : null;
    @endphp

    @if($players->isEmpty())
        <p class="text-gray-600 text-center">There are no U21 players in the database.</p>
    @else
        <div class="player-container">
        @foreach($players as $player)
    <div class="player-item relative bg-white shadow-lg rounded-lg overflow-hidden group">
        <!-- Club Logo -->
        @if($userSettings && $userSettings->logo)
            <img src="{{ asset('storage/' . $userSettings->logo) }}" alt="Club Logo" class="club-logo" style="height: 60px; width: auto;">
        @endif

        <!-- Player Image -->
        @if($player->photo)
            <img src="{{ asset('storage/' . $player->photo) }}" alt="{{ $player->first_name }} {{ $player->last_name }}" class="w-full h-48 object-cover">
        @else
            <img src="{{ asset('avatar.png') }}" alt="Default Player" class="w-full h-48 object-cover">
        @endif

        <!-- Player Number -->
        <div class="player-number absolute top-2 right-2 bg-black text-white text-lg font-bold rounded-full px-3 py-1 z-10">{{ $player->number }}</div>

        <!-- Player Name on Image -->
        <div class="player-info absolute bottom-0 left-0 right-0 bg-black bg-opacity-75 text-white p-2 z-10">
            <span>{{ $player->first_name }} {{ $player->last_name }}</span>
        </div>

        <!-- Player Info Overlay -->
        <div class="player-overlay p-4">
            <p><strong>Birthdate:</strong> {{ $player->birthdate }}</p>
            <p><strong>Position:</strong> {{ $player->position }}</p>
            <p><strong>Nationality:</strong> {{ $player->nationality }}</p>
            <p><strong>Height:</strong> {{ $player->height }} cm</p>

            <!-- Delete and Edit buttons, visible only to authenticated users -->
            @auth
                <div class="flex mt-4">
                    <!-- Delete Button -->
                    <form action="{{ route('playersu21.destroy', $player->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this player?');">
    @csrf
    @method('DELETE')
    <button type="submit" style="background-color: #DC2626; color: white; padding: 8px 16px; border-radius: 8px; margin-right: 10px; text-align: center;">
        Delete
    </button>
</form>


                    <!-- Edit Button -->
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editPlayerModal-{{ $player->id }}">
                        Edit
                    </button>
                </div>
            @endauth
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editPlayerModal-{{ $player->id }}" tabindex="-1" aria-labelledby="editPlayerModalLabel-{{ $player->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editPlayerModalLabel-{{ $player->id }}">Edit Player</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('playersu21.update', $player->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-4">
                            <label for="first_name-{{ $player->id }}" class="block text-sm font-medium text-gray-700">First Name</label>
                            <input type="text" name="first_name" id="first_name-{{ $player->id }}" value="{{ $player->first_name }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                        </div>
                        <div class="mb-4">
                            <label for="last_name-{{ $player->id }}" class="block text-sm font-medium text-gray-700">Last Name</label>
                            <input type="text" name="last_name" id="last_name-{{ $player->id }}" value="{{ $player->last_name }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                        </div>
                        <div class="mb-4">
                            <label for="photo-{{ $player->id }}" class="block text-sm font-medium text-gray-700">Photo</label>
                            @if($player->photo)
                                <img src="{{ asset('storage/' . $player->photo) }}" alt="Current Photo" class="w-20 h-20 object-cover mb-4">
                            @endif
                            <input type="file" name="photo" id="photo-{{ $player->id }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        </div>
                        <div class="mb-4">
                            <label for="birthdate-{{ $player->id }}" class="block text-sm font-medium text-gray-700">Birthdate</label>
                            <input type="date" name="birthdate" id="birthdate-{{ $player->id }}" value="{{ $player->birthdate }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                        </div>
                        <div class="mb-4">
                            <label for="position-{{ $player->id }}" class="block text-sm font-medium text-gray-700">Position</label>
                            <input type="text" name="position" id="position-{{ $player->id }}" value="{{ $player->position }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                        </div>
                        <div class="mb-4">
                            <label for="number-{{ $player->id }}" class="block text-sm font-medium text-gray-700">Number</label>
                            <input type="number" name="number" id="number-{{ $player->id }}" value="{{ $player->number }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                        </div>
                        <div class="mb-4">
                            <label for="nationality-{{ $player->id }}" class="block text-sm font-medium text-gray-700">Nationality</label>
                            <input type="text" name="nationality" id="nationality-{{ $player->id }}" value="{{ $player->nationality }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                        </div>
                        <div class="mb-4">
                            <label for="height-{{ $player->id }}" class="block text-sm font-medium text-gray-700">Height (in cm)</label>
                            <input type="number" name="height" id="height-{{ $player->id }}" value="{{ $player->height }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

        </div>
    @endif

    <x-footer />
</body>
</html>
