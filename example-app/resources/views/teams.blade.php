@php
use Illuminate\Support\Facades\Auth;
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Senior Team | {{ $clubName }}</title>
    @if($logoPath)
        <link rel="icon" href="{{ $logoPath }}" type="image/png"> <!-- Type de l'image selon le type du logo -->
    @endif
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @vite('resources/css/app.css')
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
            z-index: 20;
        }

        .staff-section {
            color: black;
            padding: 40px 0;
            text-align: center; /* Centering the section title */
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
        }

        .staff-item h3 {
            color: black;
            font-size: 30px;
            margin-bottom: 10px;
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

    <header class="text-center my-12">
    <x-page-title subtitle="👥 Meet the talented players of our Team A, each bringing their unique skills and passion to the game. ">
    Senior Squad {{ $championship->season }}
</x-page-title>

@auth
<x-button 
    route="{{ route('players.create') }}"
    buttonText="Add Player" 
    primaryColor="#DC2626" 
    secondaryColor="#B91C1C" 
/>
@endauth
    </header>

    @php
    $userSettings = Auth::check() ? Auth::user()->userSettings : null;
    @endphp

    @if($players->isEmpty())
        <p class="text-gray-600 text-center">There are no players in the database.</p>
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
            <p><strong>Contract Until:</strong> {{ \Carbon\Carbon::parse($player->contract_until)->format('d-m-Y') }}</p>

            <!-- Delete and Edit buttons, visible only to authenticated users -->
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

    <!-- Coach Section -->
    <section class="bg-bg mt-12">
    @if($coach)
        <div class="bg-coach flex items-center justify-between text-gray-700">
            <!-- Coach Information -->
            <div class="coach-details text-gray-700">
            <x-page-title subtitle="L'entraîneur est le cœur battant qui insuffle motivation et discipline">
    Headcoach
</x-page-title>
                <h3 class="text-3xl font-bold mb-6">{{ $coach->first_name }} {{ $coach->last_name }}</h3>
                <p class="text-lg  mb-6">{!! $coach->description !!}</p>

                <div class="text-lg coach-info">
                    <p class="mb-2"><strong>Date of Birth:</strong> {{ \Carbon\Carbon::parse($coach->birth_date)->format('d F Y') }}</p>
                    <p class="mb-2"><strong>Place of Birth:</strong> {{ $coach->birth_city }}</p>
                    <p class="mb-2"><strong>Nationality:</strong> {{ $coach->nationality }}</p>
                    <p class="mb-2"><strong>Coaching since:</strong> {{ \Carbon\Carbon::parse($coach->coaching_since)->format('d F Y') }}</p>
                </div>
            </div>

            <!-- Coach Photo and Buttons -->
            <div class="text-center flex flex-col items-center justify-center">
                @if($coach->photo)
                    <img src="{{ asset('storage/' . $coach->photo) }}" alt="{{ $coach->first_name }} {{ $coach->last_name }}" class="coach-photo">
                @else
                    <img src="https://via.placeholder.com/256" alt="No photo available" class="coach-photo">
                @endif

                <!-- Buttons for Edit and Delete -->
                @auth
                    <div class="flex mt-6">
                        <!-- Edit Button -->
                        <a href="{{ route('coaches.edit', $coach->id) }}" 
                           style="background-color: #2563EB; color: white; padding: 8px 16px; border-radius: 8px; text-align: center; margin-right: 8px;">
                            Edit
                        </a>

                        <!-- Delete Button -->
                        <form action="{{ route('coaches.destroy', $coach->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this coach?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background-color: #DC2626; color: white; padding: 8px 16px; border-radius: 8px; text-align: center;">
                                Delete
                            </button>
                        </form>
                    </div>
                @endauth
            </div>
        </div>
    @else
        <p class="text-gray-600 text-center">No coach available.</p>
    @endif
</section>


    <!-- Staff Section -->
    <section class="staff-section">
    <x-page-title subtitle="They ensure that every player is at their best, both on and off the field">
    Technical and Medical Staff
</x-page-title>

        <div class="staff-container">
            @foreach($staff as $member)
            <div class="staff-item">
                <h3>{{ $member->first_name }} {{ $member->last_name }}</h3>
                <p>{{ $member->position }}</p>

                @auth
                <div class="mt-4 flex justify-center space-x-4">
    <!-- Delete Button -->
    <form action="{{ route('staff.destroy', $member->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this staff member?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="bg-red-500 text-white font-bold py-2 px-4 rounded">Delete</button>
    </form>

    <!-- Edit Button -->
    <a href="{{ route('staff.edit', $member->id) }}" class="bg-blue-500 text-white font-bold py-2 px-4 rounded">Edit</a>
</div>
                @endauth
            </div>
            @endforeach
        </div>
    </section>
    <x-footer />
</body>
</html>
