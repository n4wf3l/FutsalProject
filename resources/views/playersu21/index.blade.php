@php
use Illuminate\Support\Facades\Auth;
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.u21_team_title') }} | {{ $clubName }}</title>
    @if($logoPath)
        <link rel="icon" href="{{ $logoPath }}" type="image/png"> <!-- Type de l'image selon le type du logo -->
    @endif
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

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
            z-index: 20;
        }

        /* Responsive Styles */
        @media (max-width: 1200px) {
            .player-container {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 768px) {
            .player-container {
                grid-template-columns: repeat(2, 1fr);
            }

            .player-info {
                font-size: 25px;
            }

            .player-number {
                width: 50px;
                height: 50px;
                font-size: 30px;
            }
        }

        @media (max-width: 480px) {
            .player-container {
                grid-template-columns: 1fr;
            }

            .player-info {
                font-size: 20px;
            }

            .player-number {
                width: 40px;
                height: 40px;
                font-size: 20px;
            }
        }
    </style>
</head>
<body class="bg-gray-100" @if($backgroundImage) style="background: url('{{ asset('storage/' . $backgroundImage->image_path) }}') no-repeat center center fixed; background-size: cover;" @endif>
    <x-navbar />

    <header class="text-center my-12">
        <x-page-title subtitle="{{ __('messages.u21_subtitle') }}">
            {{ __('messages.u21_team_title') }} {{ $championship->season ?? 'N/A' }}
        </x-page-title>

        @auth
            <a href="{{ route('playersu21.create') }}" class="text-white font-bold py-2 px-6 rounded-full transition duration-200 shadow-lg text-center button-hover-primary" style="font-size:20px; background-color: {{ $primaryColor }};"
               onmouseover="this.style.backgroundColor='{{ $secondaryColor }}'"
               onmouseout="this.style.backgroundColor='{{ $primaryColor }}'">
                {{ __('messages.add_u21_player') }}
            </a>
        @endauth
    </header>

    @php
    $userSettings = Auth::check() ? Auth::user()->userSettings : null;
    @endphp

    @if($players->isEmpty())
        <p class="text-gray-600 text-center">{{ __('messages.no_u21_players') }}</p>
    @else
        <div class="player-container" data-aos="fade-up">
            @foreach($players as $player)
                <div class="player-item relative bg-white shadow-lg rounded-lg overflow-hidden group">
                    <!-- Club Logo -->
                    @if($userSettings && $userSettings->logo)
                        <img src="{{ asset('storage/' . $userSettings->logo) }}" alt="{{ __('messages.club_logo_alt') }}" class="club-logo" style="height: 60px; width: auto;">
                    @endif

                    <!-- Player Image -->
                    @if($player->photo)
                        <img src="{{ asset('storage/' . $player->photo) }}" alt="{{ $player->first_name }} {{ $player->last_name }}" class="w-full h-48 object-cover">
                    @else
                        <img src="{{ asset('avatar.png') }}" alt="{{ __('messages.default_player_alt') }}" class="w-full h-48 object-cover">
                    @endif

                    <!-- Player Number -->
                    <div class="player-number absolute top-2 right-2 bg-black text-white text-lg font-bold rounded-full px-3 py-1 z-10">{{ $player->number }}</div>

                    <!-- Player Name on Image -->
                    <div class="player-info absolute bottom-0 left-0 right-0 bg-black bg-opacity-75 text-white p-2 z-10">
                        <span>{{ $player->first_name }} {{ $player->last_name }}</span>
                    </div>

                    <!-- Player Info Overlay -->
                    <div class="player-overlay p-4">
                        <p><strong>{{ __('messages.birthdate') }}:</strong> {{ $player->birthdate }}</p>
                        <p><strong>{{ __('messages.position') }}:</strong> {{ $player->position }}</p>
                        <p><strong>{{ __('messages.nationality') }}:</strong> {{ $player->nationality }}</p>
                        <p><strong>{{ __('messages.height') }}:</strong> {{ $player->height }} cm</p>

                        <!-- Delete and Edit buttons, visible only to authenticated users -->
                        @auth
                            <div class="flex mt-4">
                                <!-- Delete Button -->
                                <form action="{{ route('playersu21.destroy', $player->id) }}" method="POST" onsubmit="return confirm('{{ __('messages.confirm_delete_player') }}');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="background-color: #DC2626; color: white; padding: 8px 16px; border-radius: 8px; margin-right: 10px; text-align: center;">
                                        {{ __('messages.delete') }}
                                    </button>
                                </form>

                                <!-- Edit Button -->
                                <a href="{{ route('playersu21.edit', $player->id) }}" style="background-color: #1D4ED8; color: white; padding: 8px 16px; border-radius: 8px; text-align: center;">
                                    {{ __('messages.edit') }}
                                </a>
                            </div>
                        @endauth
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <x-footerforhome />
</body>
</html>
