@php
use Illuminate\Support\Facades\Auth;
@endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.senior_team') }} | {{ $clubName }}</title>
    @if($logoPath)
        <link rel="icon" href="{{ $logoPath }}" type="image/png"> <!-- Type de l'image selon le type du logo -->
    @endif
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <!-- Meta Tags for SEO -->
<meta name="description" content="Discover the senior team of {{ $clubName }} for the {{ $championship->season ?? 'N/A' }} season. Get to know the players, the coach, and the technical staff.">
<meta name="keywords" content="senior team, {{ $clubName }}, {{ $championship->season ?? 'N/A' }}, futsal, players, coach, technical staff">
<meta property="og:title" content="{{ __('messages.senior_team') }} | {{ $clubName }}">
<meta property="og:description" content="Meet the senior squad of {{ $clubName }} for the {{ $championship->season ?? 'N/A' }} season. Explore player profiles, coaching details, and staff information.">
<meta property="og:url" content="{{ url()->current() }}">
<meta name="robots" content="index, follow">
<link rel="canonical" href="{{ url()->current() }}">

    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        .bg-coach {
            background-color: white;
            padding: 40px;
            border-radius: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            text-align: left;
            margin-top: 50px;
            max-width: 1200px;
            margin-left: auto;
            margin-right: auto;
        }

        .coach-photo {
            max-width: 200px; /* Redimensionner la photo à une largeur maximale de 200px */
            height: auto;
            border-radius: 12px;
            margin-left: 20px; /* Pour espacer la photo du texte */
        }

        .coach-details {
            flex: 1;
        }

        .coach-info {
            margin-top: 20px;
        }

        .coach-info strong {
            font-weight: 600;
        }

        .zoom-hover {
    transition: transform 0.3s ease;
}

.zoom-hover:hover {
    transform: scale(1.05);
}

/* Appliquer le style de zoom aux éléments */
.player-item, .bg-coach, .staff-item {
    transition: transform 0.3s ease;
}

.player-item:hover, .bg-coach:hover, .staff-item:hover {
    transform: scale(1.05);
}

        .player-container {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            justify-items: center;
            margin: 0 auto;
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
            background-color: rgba(0, 0, 0, 0.5);
            color: white;
            width: 60px;
            height: 60px;
            font-size: 40px;
            font-weight: bold;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
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
    z-index: 50; /* Augmentation du z-index */
}

@media (max-width: 768px) {
    .club-logo {
        display: block !important; /* Assurez-vous que le logo soit affiché sur mobile */
        width: 15%; /* Ajustement de la taille si nécessaire */
    }
}
.staff-section {
    color: black;
    padding: 40px 20px; /* Ajoute un padding horizontal pour mobile */
    text-align: center;
}

        .staff-title {
            font-size: 50px;
            margin-top: 50px;
            text-align: center;
        }

        .staff-container {
    max-width: 1600px;
    margin: 50px auto;
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
}

.staff-item {
    background-color: white;
    padding: 20px;
    border-radius: 8px;
    text-align: center;
    height: 700px; /* Hauteur fixe pour le conteneur */
    overflow: hidden; /* Cache le dépassement si nécessaire */
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.staff-item h3 {
    color: black;
    font-size: 30px;
    margin: 10px 0;
}

.staff-item p {
    color: black;
    font-size: 20px;
    margin-bottom: 15px;
}

.staff-item button,
.staff-item a {
    display: inline-block;
    padding: 10px 20px;
    border-radius: 8px;
    font-size: 16px;
    margin-right: 10px;
}

.staff-item button {
    background-color: #DC2626;
    color: white;
}

.staff-item a {
    color: white;
    text-decoration: none;
}

.staff-item a:hover,
.staff-item button:hover {
    opacity: 0.8;
}

.framed-photo {
    max-width: 200px; /* Contrôle la taille de l'image */
    height: auto;
    border-radius: 12px; /* Donne des coins arrondis */
    padding: 10px; /* Espace entre l'image et le cadre */
    background: {{ $secondaryColor }}; /* Dégradé pour le cadre */
    box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.3); /* Ombre pour donner de la profondeur */
}

.uniform-photo {
    width: 100%;
    height: 500px; /* Hauteur fixe pour l'image */
    object-fit: cover; /* Remplit le conteneur sans déformation */
    object-position: top; /* Priorise le haut de l'image */
    border-radius: 8px; /* Pour s'harmoniser avec le conteneur */
}

        /* Responsive Styles */
        @media (max-width: 1200px) {
            .bg-coach {
                flex-direction: column;
                text-align: center;
            }

            .coach-photo {
                margin-left: 0;
                margin-bottom: 20px;
            }

            .player-container {
                grid-template-columns: repeat(3, 1fr);
            }

            .staff-container {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .bg-coach {
                padding: 20px;
            }

            .coach-photo {
                max-width: 150px;
                margin-bottom: 20px;
            }

            .player-container {
                grid-template-columns: repeat(2, 1fr);
            }

            .staff-container {
                grid-template-columns: repeat(1, 1fr);
            }

            .staff-title {
                font-size: 1.875rem;
                text-align: center;
                margin-bottom: 1rem;
            }
        }

        @media (max-width: 480px) {
            .player-container {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body class="bg-gray-100" @if($backgroundImage) style="background: url('{{ asset('storage/' . $backgroundImage->image_path) }}') no-repeat center center fixed; background-size: cover;" @endif>
    <x-navbar />

    <header class="text-center my-12 mx-auto px-4 max-w-md">
        <x-page-title subtitle="{{ __('messages.meet_players') }}">
            {{ __('messages.senior_squad') }} {{ $championship->season ?? 'N/A' }}
        </x-page-title>

        @auth
            <x-button 
                route="{{ route('players.create') }}"
                buttonText="{{ __('messages.add_player') }}" 
                primaryColor="#DC2626" 
                secondaryColor="#B91C1C" 
            />
        @endauth
    </header>

    @php
    $userSettings = Auth::check() ? Auth::user()->userSettings : null;
    @endphp

    @if($players->isEmpty())
        <p class="text-gray-600 text-center">{{ __('messages.no_players') }}</p>
    @else
        <div class="player-container" data-aos="fade-up">
            @foreach($players as $player)
                <div class="player-item relative bg-white shadow-lg rounded-lg overflow-hidden group transform transition-transform duration-200 hover:scale-105">
                    <!-- Club Logo -->
                    @if($userSettings && $userSettings->logo)
                        <img src="{{ asset('storage/' . $userSettings->logo) }}" alt="{{ __('messages.club_logo') }}" class="club-logo" style="height: 60px; width: auto;">
                    @endif

                    <!-- Player Image -->
                    @if($player->photo)
                        <img src="{{ asset('storage/' . $player->photo) }}" alt="{{ $player->first_name }} {{ $player->last_name }}" class="w-full h-48 object-cover">
                    @else
                        <img src="{{ asset('avatar.png') }}" alt="{{ __('messages.default_player') }}" class="w-full h-48 object-cover">
                    @endif

                    <!-- Player Number -->
                    <div class="player-number absolute top-2 right-2 bg-black text-white text-lg font-bold rounded-full px-3 py-1 z-10">{{ $player->number }}</div>

                    <!-- Player Name on Image -->
                    <div class="player-info absolute bottom-0 left-0 right-0 bg-black bg-opacity-75 text-white p-2 z-10">
                        <span>{{ $player->first_name }} {{ $player->last_name }}</span>
                    </div>

            <!-- Player Info Overlay with unique ID -->
            <div id="player-info-{{ $player->id }}" class="player-overlay p-4 hidden">
                <p><strong>{{ __('messages.birthdate') }}:</strong> {{ \Carbon\Carbon::parse($player->birthdate)->format('d-m-Y') }}</p>
                <p><strong>{{ __('messages.position') }}:</strong> {{ $player->position }}</p>
                <p><strong>{{ __('messages.nationality') }}:</strong> {{ $player->nationality }}</p>

                <script>
    function togglePlayerInfo(playerId) {
        const playerInfo = document.getElementById(`player-info-${playerId}`);
        if (playerInfo) {
            playerInfo.classList.toggle('hidden');
        }
    }
</script>
                  <!-- Delete and Edit buttons, visible only to authenticated users -->
                  @auth
<div class="flex mt-4">
    <!-- Edit Button -->
    <a href="{{ route('players.edit', $player->id) }}" 
       class="transform hover:scale-110 transition duration-200" 
       title="{{ __('messages.edit') }}"
       style="background-color: #d1ecf1; border: 1px solid #bee5eb; padding: 8px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-right: 10px;">
        <i class="fas fa-edit text-blue-600 hover:text-blue-800 text-xl"></i>
    </a>

    <!-- Delete Button -->
    <form action="{{ route('players.destroy', $player->id) }}" method="POST" onsubmit="return confirm('{{ __('messages.delete_confirmation') }}');">
        @csrf
        @method('DELETE')
        <button type="submit" 
                class="transform hover:scale-110 transition duration-200" 
                title="{{ __('messages.delete') }}"
                style="background-color: #f8d7da; border: 1px solid #f5c6cb; padding: 8px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center;">
            <i class="fas fa-trash-alt text-red-600 hover:text-red-800 text-xl"></i>
        </button>
    </form>
</div>
@endauth

                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Coach Section -->
    <section class="bg-bg mt-12" data-aos="flip-right">
        @if($coach)
            <div class="bg-coach flex items-center justify-between text-gray-700 transform transition-transform duration-200 hover:scale-105">
                <!-- Coach Information -->
                <div class="coach-details text-gray-700">
                    <x-page-title subtitle="{{ __('messages.coach_subtitle') }}">
                        {{ __('messages.head_coach') }}
                    </x-page-title>
                    <h3 class="text-3xl font-bold mb-6">{{ $coach->first_name }} {{ $coach->last_name }}</h3>
                    <p class="text-lg  mb-6">{!! $coach->description !!}</p>

                    <div class="text-lg coach-info">
                        <p class="mb-2"><strong>{{ __('messages.place_of_birth') }}:</strong> {{ $coach->birth_city }}</p>
                        <p class="mb-2"><strong>{{ __('messages.nationality') }}:</strong> {{ $coach->nationality }}</p>
                        <p class="mb-2"><strong>{{ __('messages.coaching_since') }}:</strong> {{ \Carbon\Carbon::parse($coach->coaching_since)->format('d F Y') }}</p>
                    </div>
                </div>

                <!-- Coach Photo and Buttons -->
                <div class="text-center flex flex-col items-center justify-center">
                    @if($coach->photo)
                    <img src="{{ asset('storage/' . $coach->photo) }}" alt="{{ $coach->first_name }} {{ $coach->last_name }}" class="coach-photo framed-photo">
                    @else
                        <img src="https://via.placeholder.com/256" alt="{{ __('messages.no_photo') }}" class="coach-photo">
                    @endif

                    <!-- Buttons for Edit and Delete -->
                    @auth
                    <div class="flex mt-6">
    <!-- Edit Button -->
    <a href="{{ route('coaches.edit', $coach->id) }}" 
       class="transform hover:scale-110 transition duration-200" 
       title="{{ __('messages.edit') }}"
       style="background-color: #d1ecf1; border: 1px solid #bee5eb; padding: 8px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-right: 10px;">
        <i class="fas fa-edit text-blue-600 hover:text-blue-800 text-xl"></i>
    </a>

    <!-- Delete Button -->
    <form action="{{ route('coaches.destroy', $coach->id) }}" method="POST" onsubmit="return confirm('{{ __('messages.delete_confirmation') }}');">
        @csrf
        @method('DELETE')
        <button type="submit" 
                class="transform hover:scale-110 transition duration-200" 
                title="{{ __('messages.delete') }}"
                style="background-color: #f8d7da; border: 1px solid #f5c6cb; padding: 8px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center;">
            <i class="fas fa-trash-alt text-red-600 hover:text-red-800 text-xl"></i>
        </button>
    </form>
</div>

@endauth

                </div>
            </div>
            @else
    <p class="text-gray-600 text-center">{{ __('messages.no_coach') }}</p>
    @auth
        <div class="flex justify-center mt-4">
            <x-button 
                route="{{ route('coaches.create') }}"
                buttonText="{{ __('messages.add_coach') }}" 
                primaryColor="#DC2626" 
                secondaryColor="#B91C1C"
            />
        </div>
    @endauth
@endif

    </section>

    <!-- Staff Section -->
    <section class="staff-section" data-aos="flip-left" id="staff-section">
        <div class="mx-auto px-2 max-w-md">
        <x-page-title subtitle="{{ __('messages.staff_subtitle') }}">
            {{ __('messages.technical_staff') }}
        </x-page-title>
</div>
        @auth
        <x-button 
            route="{{ route('staff.create') }}"
            buttonText="{{ __('messages.add_staff') }}" 
            primaryColor="#DC2626" 
            secondaryColor="#B91C1C" 
        />
    @endauth

        @if($staff->isEmpty()) 
    <p class="text-gray-600 text-center">{{ __('messages.no_staff') }}</p>
@else
    <div class="staff-container">
    @foreach($staff as $member)
    <div class="staff-item relative bg-white shadow-lg rounded-lg overflow-hidden transform transition-transform duration-200 hover:scale-105">
        <div class="relative">
            <!-- Logo du club en haut à gauche de l'image -->
            @if($userSettings && $userSettings->logo)
                <img src="{{ asset('storage/' . $userSettings->logo) }}" alt="{{ __('messages.club_logo') }}" class="club-logo" style="height: 60px; width: auto; position: absolute; top: 10px; left: 10px; z-index: 10;">
            @endif

            <!-- Photo du membre du staff -->
            @if($member->photo)
                <img src="{{ asset('storage/' . $member->photo) }}" alt="{{ $member->first_name }} {{ $member->last_name }}" class="uniform-photo">
            @else
                <img src="{{ asset('avatar.png') }}" alt="{{ __('messages.default_player') }}" class="uniform-photo">
            @endif
        </div>

        <!-- Informations du membre -->
        <h3>{{ $member->first_name }} {{ $member->last_name }}</h3>
        <p>{{ $member->position }}</p>

        <!-- Boutons Modifier et Supprimer -->
        @auth
    <div class="mt-4 flex justify-center space-x-4">
      <!-- Bouton Supprimer -->
<form action="{{ route('staff.destroy', $member->id) }}" method="POST" onsubmit="return confirm('{{ __('messages.delete_confirmation') }}');">
    @csrf
    @method('DELETE')
    <button type="submit" class="transform hover:scale-110 transition duration-200" title="{{ __('messages.delete') }}" 
            style="background-color: #f8d7da; border: 1px solid #f5c6cb; padding: 8px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center;">
        <i class="fas fa-trash-alt text-red-600 hover:text-red-800 text-xl"></i>
    </button>
</form>

<!-- Bouton Modifier -->
<a href="{{ route('staff.edit', $member->id) }}" class="transform hover:scale-110 transition duration-200" title="{{ __('messages.edit') }}" 
   style="background-color: #d1ecf1; border: 1px solid #bee5eb; padding: 8px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center;">
    <i class="fas fa-edit text-blue-600 hover:text-blue-800 text-xl"></i>
</a>

    </div>
@endauth
    </div>
@endforeach
    </div>
@endif

    </section>

    <x-footer />
</body>
</html>
