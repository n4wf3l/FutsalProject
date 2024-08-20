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

/* Assurez-vous que le modal a un z-index plus élevé */
.modal {
    z-index: 1500 !important;
}

.modal-backdrop {
    z-index: 1400;
}

        .background-container {
            position: relative;
    width: 100%;
    min-height: 60vh;
    @if($backgroundImage)
        background: url('{{ asset('storage/' . $backgroundImage->image_path) }}') no-repeat center center;
        background-size: cover;
    @else
        background-color: #f8f9fa;
    @endif
        }

        .background-container,
.cover-container,
.info-container {
    z-index: 1100; /* S'assurer que c'est en dessous des modals */
}


        .cover-container {
            position: relative;
    width: 100%;
    min-height: 40vh;
    background-color: #f1f1f1;
        }

        /* White container positioned at the center */
        .info-container {
            border: 4px solid {{ $primaryColor }};
    border-radius: 15px;
    background-color: white;
    width: 40%;
    padding: 20px;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    position: absolute;
    top: 60%;
    left: 50%;
    transform: translate(-50%, -50%);
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
    z-index: 300;
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
            z-index: 300;
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
    <li class="d-flex align-items-center justify-content-center">
    <img src="{{ asset('weather.png') }}" alt="Position" class="h-6 w-6 mr-2 ml-2"> 
    <div class="mr-2">{{ $city }} – <span style="font-weight: bold;">{{ $weatherData['main']['temp'] ?? 'N/A' }}°C</span></div>
        @auth
            <button type="button" class="btn btn-sm btn-light mr-2" data-bs-toggle="modal" data-bs-target="#editFlashMessageModal">
                EDIT
            </button>
            <!-- Button trigger modal -->
<button type="button" class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#addImageModal">
    Add Image
</button>
</li>


<!-- Modal pour ajouter une image -->

        @endauth
    </div>
    <div class="flash-message">
        {{ $flashMessage->message ?? 'Bienvenue sur notre site web !' }}
    </div>
</div>

<div class="modal fade" id="addImageModal" tabindex="-1" aria-labelledby="addImageModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('welcome-image.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addImageModalLabel">Add PNG Image</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="image" class="form-label">Select PNG Image</label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/png" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Upload Image</button>
                </div>
            </div>
        </form>
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

<div class="background-container" style="position: relative; width: 100%; height: 60vh; background: url('{{ asset('storage/' . $backgroundImage->image_path) }}') no-repeat center center; background-size: cover; z-index: 500;">
    @if($welcomeImage)
        <img src="{{ asset('storage/' . $welcomeImage->image_path) }}" alt="Welcome Image" class="welcome-image" style="position: absolute; bottom: 40px; right: 100px; width: 550px; height: 500px;">
    @endif
    <div id="typing-text" style="position: absolute; top: 10%; left: 10%; color: {{ $secondaryColor }}; font-family: 'Bebas Neue', sans-serif; font-size: 6rem; font-weight: bold; text-align: left; text-shadow: 2px 2px 5px rgba(0,0,0,0.7); z-index: 1300;">
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const text = "DINA KÉNITRA FC\nRISE UP & DOMINATE\nTHE GAME!";
    const typingElement = document.getElementById("typing-text");
    let index = 0;
    const speed = 100;  // Vitesse d'écriture (ms)

    function typeWriter() {
        if (index < text.length) {
            const char = text.charAt(index);
            if (char === '\n') {
                typingElement.innerHTML += '<br>';
            } else {
                typingElement.innerHTML += char;
            }
            index++;
            setTimeout(typeWriter, speed);
        } else {
            typingElement.style.borderRight = "none";

            const logo = document.createElement("img");
            logo.src = "{{ $logoPath ? asset($logoPath) : '' }}"; // Récupérer et utiliser $logoPath ici
            logo.alt = "Club Logo";
            logo.style.width = "150px"; // Taille du logo
            logo.style.position = "absolute";
            logo.style.left = "50%";
            logo.style.transform = "translateX(-50%)";
            logo.style.marginTop = "10vh";
            logo.style.opacity = "0";  // Commence transparent
            logo.style.transition = "opacity 2s ease-in-out";

            // Vérifiez que le logoPath est valide avant de l'ajouter
            if (logo.src && logo.src !== '') {
                typingElement.parentNode.appendChild(logo);

                // Déclencher l'animation de fondu
                setTimeout(() => {
                    logo.style.opacity = "1";
                }, 100);
                
                // Ajouter le bouton après un délai pour afficher le logo d'abord
                setTimeout(function() {
                    const button = document.createElement("a");
                    button.id = "reserve-button";
                    button.href = "{{ route('fanshop.index') }}";
                    button.innerHTML = "Reserve your ticket";
                    button.style.display = "inline-block";
                    button.style.padding = "15px 30px";
                    button.style.backgroundColor = "{{ $secondaryColor }}";
                    button.style.color = "white";
                    button.style.fontFamily = "'Bebas Neue', sans-serif";
                    button.style.fontSize = "1.5rem";
                    button.style.border = "none";
                    button.style.borderRadius = "5px";
                    button.style.cursor = "pointer";
                    button.style.textDecoration = "none";
                    button.style.position = "absolute";
                    button.style.left = "50%";
                    button.style.transform = "translateX(-50%)";
                    button.style.marginTop = "30vh";
                    button.style.opacity = "0"; 
                    button.style.transition = "opacity 2s ease-in-out";

                    button.addEventListener("mouseover", function() {
                        button.style.backgroundColor = "{{ $primaryColor }}";
                    });

                    button.addEventListener("mouseout", function() {
                        button.style.backgroundColor = "{{ $secondaryColor }}";
                    });

                    typingElement.parentNode.appendChild(button);

// Déclencher l'animation de fondu pour le bouton
setTimeout(() => {
    button.style.opacity = "1";
}, 100); // Légère pause pour que le bouton s'ajoute avant la transition

}, 1000); // Le bouton apparaît 1 seconde après le logo
            }
        }
    }

    typeWriter();
});
</script>

<style>
/* Effet d'écriture avec curseur clignotant */
#typing-text {
    white-space: nowrap;
    overflow: hidden;
    border-right: 4px solid rgba(255, 255, 255, 0.75);
    animation: cursor-blink 0.7s infinite;
}

@keyframes cursor-blink {
    0% { border-right-color: rgba(255, 255, 255, 0.75); }
    50% { border-right-color: transparent; }
    100% { border-right-color: rgba(255, 255, 255, 0.75); }
}

/* Style du bouton */
#reserve-button {
    margin-top: 30vh;
    padding: 15px 30px;
    background-color: {{ $secondaryColor }};
    color: white;
    font-family: 'Bebas Neue', sans-serif;
    font-size: 1.5rem;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
    text-align: center;
    position: absolute;
    left: 50%;
    transform: translateX(-50%);
}

#reserve-button:hover {
    background-color: {{ $primaryColor }};
}

/* Animation de bascule de l'image */
@keyframes rotate-image {
    0% { transform: rotate(0deg); }
    25% { transform: rotate(2deg); }
    50% { transform: rotate(0deg); }
    75% { transform: rotate(-2deg); }
    100% { transform: rotate(0deg); }
}

.welcome-image {
    animation: rotate-image 4s infinite;
}
</style>


    <div class="info-container mt-60" style="z-index: 1300;">
        <div class="title">NEXT GAME</div>
        @if($nextGame)
        <div class="match-info">
            <!-- Affichage du lieu du match -->
            @if(Str::startsWith($nextGame->homeTeam->name, $clubPrefix))
            <li class="d-flex align-items-center justify-content-center">
    <img src="{{ asset('position.png') }}" alt="Position" class="h-6 w-6 mr-2"> 
    <div class="match-location">{{ $clubLocation }}, {{ $city }}</div>
</li>
            @else
            <li class="d-flex align-items-center justify-content-center">
    <img src="{{ asset('position.png') }}" alt="Position" class="h-6 w-6 mr-2"> 
    <div class="match-location">Away</div>
</li>
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
            <li class="d-flex align-items-center justify-content-center">
    <img src="{{ asset('date.png') }}" alt="Position" class="h-6 w-6 mr-2"> 
    <div class="match-location">{{ \Carbon\Carbon::parse($nextGame->match_date)->format('d-m-Y') }}</div>
</li>
        </div>
        @else
            <div class="no-match">
                <p>No upcoming games scheduled.</p>
            </div>
        @endif
    </div>
</div>

<!-- Cover Container with Main Article -->
<div class="cover-container mt-40" style="position: relative; width: 100%; min-height: 70vh; background-color: #f1f1f1; display: flex; justify-content: center; align-items: center; padding: 20px; z-index: 1000;">
    <div class="main-article-container" style="width: 100%; max-width: 1200px; display: flex; flex-direction: row; align-items: flex-start;">
        <!-- Main Article Image -->
        <div class="main-article-image" style="flex: 1; margin-right: 20px;">
            <a href="{{ route('articles.show', $articles->first()->slug) }}">
                @if($articles->first()->image)
                    <img src="{{ asset('storage/' . $articles->first()->image) }}" alt="{{ $articles->first()->title }}" style="width: 100%; height: auto; border-radius: 8px;">
                @endif
            </a>
        </div>

        <!-- Main Article Content -->
        <div class="main-article-content" style="flex: 2; padding-left: 20px; display: flex; flex-direction: column; justify-content: center;">
            <p style="font-size: 1rem; color: #6b7280; font-family: 'Bebas Neue', sans-serif; text-transform: uppercase; margin-bottom: 10px;">
                {{ $clubName }} - Recent News
            </p>
            <h2 class="text-3xl font-bold mb-2 article-title" style="font-size: 2.5rem; color: {{ $primaryColor }}; font-family: 'Bebas Neue', sans-serif; margin-bottom: 20px;">
                <strong>{{ $articles->first()->title }}</strong>
            </h2>
            <p class="text-sm text-gray-500" style="font-size: 1rem; color: #6b7280; margin-bottom: 15px;">
                Published on: {{ $articles->first()->created_at->format('d M Y, H:i') }} by {{ $articles->first()->user->name }}
            </p>
            <p class="text-gray-600 mb-4" style="color: #6b7280; margin-top: 20px;">
                {!! \Illuminate\Support\Str::limit(strip_tags($articles->first()->description, '<b><i><strong><em><ul><li><ol>'), 400) !!}
            </p>

            <!-- Buttons Container -->
            <div class="buttons-container" style="margin-top: 30px; display: flex; justify-content: flex-start; gap: 20px;">
                <a href="{{ route('clubinfo') }}" class="btn btn-primary" style="background-color: {{ $primaryColor }}; color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none; transition: transform 0.3s, background-color 0.3s;">
                    News
                </a>
                <a href="{{ route('articles.show', $articles->first()->slug) }}" class="btn btn-secondary" style="background-color: {{ $secondaryColor }}; color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none; transition: transform 0.3s, background-color 0.3s;">
                    Read More
                </a>
            </div>

            <div style="display: none;">
                <style>
                    .buttons-container .btn-primary:hover {
                        transform: scale(1.1);
                        background-color: {{ $secondaryColor }};
                    }

                    .buttons-container .btn-secondary:hover {
                        transform: scale(1.1);
                        background-color: {{ $primaryColor }};
                    }
                </style>
            </div>
        </div>
    </div>
</div>

</div>
<hr>
 <!-- Section pour les 5 prochains matchs -->
 <div class="containerization mt-5">
    <h2 class="upcoming-title">Upcoming Matches</h2>
    @if($nextGames->isNotEmpty())
        @foreach($nextGames as $index => $game)
            <div class="match-card {{ $index % 2 == 0 ? 'even' : 'odd' }}">
                <div class="match-date">
                    {{ \Carbon\Carbon::parse($game->match_date)->format('d F Y') }}
                </div>
                <div class="match-location">
                    @if(Str::startsWith($game->homeTeam->name, $clubPrefix))
                        {{ $clubLocation }} (Home)
                    @else
                        Away
                    @endif
                </div>
                <div class="match-details">
                    <div class="team-logo">
                        <img src="{{ asset('storage/' . $game->homeTeam->logo_path) }}" alt="{{ $game->homeTeam->name }} Logo">
                    </div>
                    <div class="match-info">
                        <p>{{ $game->homeTeam->name }} <strong>vs</strong> {{ $game->awayTeam->name }}</p>
                    </div>
                    <div class="team-logo">
                        <img src="{{ asset('storage/' . $game->awayTeam->logo_path) }}" alt="{{ $game->awayTeam->name }} Logo">
                    </div>
                </div>
            </div>
        @endforeach
        <div class="see-all-container">
            <a href="{{ route('calendar.show') }}" class="see-all-btn">See All Matches</a>
        </div>
    @else
        <p>Aucun match à venir.</p>
    @endif
</div>

<style>
.containerization {
    width: 100%;
    max-width: 900px;
    margin: auto;
    border: 4px solid {{ $primaryColor }};
    border-radius: 15px;
    margin-bottom: 20px;
}

.upcoming-title {
    text-align: center;
    font-size: 2rem;
    font-weight: bold;
    color: {{ $primaryColor }};
    margin-top: 15px;  /* Réduit l'espace au-dessus du titre */
    margin-bottom: 15px; /* Réduit l'espace en dessous du titre */
    font-family: 'Arial', sans-serif;
}

.match-card {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    padding: 2px; /* Réduit le padding vertical */
    text-align: center;
    transition: background-color 0.3s ease;
    border-bottom: 1px solid #ddd; /* Séparation entre les cartes */
}

.match-card.even {
    background-color: #f9f9f9;
}

.match-card.odd {
    background-color: #e9e9e9;
}

.match-card:hover {
    background-color: rgba(0, 0, 0, 0.05);
}

.match-date {
    font-size: 14px;
    color: #888;
    margin-bottom: 2px; /* Réduit l'espace en dessous de la date */
    font-weight: bold;
}

.match-details {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
    margin-bottom: 2px; /* Réduit l'espace en dessous des détails du match */
    padding: 0 20px; /* Réduit le padding latéral pour centrer le texte entre les logos */
}

.team-logo img {
    width: 75px; /* Ajuste légèrement la taille des logos */
    height: 75px;
    object-fit: contain;
    transition: transform 0.3s ease; /* Ajoute une transition au survol */
}

.match-info {
    font-size: 18px;
    font-weight: bold;
    color: #333;
    text-align: center;
    flex-grow: 1; /* Fait que le texte occupe l'espace restant */
    line-height: 1.2; /* Réduit l'espacement vertical du texte */
}

.match-location {
    font-size: 16px;
    color: #555;
    font-weight: bold;
    margin-top: 5px; /* Réduit l'espace au-dessus de l'emplacement */
}

.see-all-container {
    text-align: center;
    margin: 15px 0; /* Réduit l'espace autour du bouton "See All Matches" */
}

.see-all-btn {
    display: inline-block;
    padding: 8px 16px; /* Réduit le padding pour un bouton plus compact */
    background-color: {{ $primaryColor }};
    color: white;
    text-transform: uppercase;
    font-weight: bold;
    border-radius: 5px;
    text-decoration: none;
    transition: background-color 0.3s ease;
}

.see-all-btn:hover {
    background-color: {{ $secondaryColor }};
}
</style>


    <!-- Footer -->
    <x-footer />

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
