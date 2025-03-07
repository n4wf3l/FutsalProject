<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.fanshop') }} | {{ $clubName }}</title>
    @if($logoPath)
        <link rel="icon" href="{{ $logoPath }}" type="image/png">
    @endif
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- Meta Tags for SEO -->
    <meta name="description" content="{{ __('messages.fanshop') }} - Explore the fanshop of {{ $clubName }}. Get your tickets for the next match and support your team at {{ $clubLocation }}.">
    <meta name="keywords" content="fanshop, {{ $clubName }}, {{ $clubLocation }}, tickets, futsal, sports"> 
    <meta property="og:title" content="{{ __('messages.fanshop') }} - {{ $clubName }} in {{ $clubLocation }}">
    <meta property="og:description" content="{{ __('messages.fanshop') }} - Purchase your match tickets and show your support for {{ $clubName }}.">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    <style>
        .fanshop-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 40px;
        }

        .stadium-plan {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
        }

        .tribune-list {
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
            width: 100%;
            max-width: 1000px;
            margin: 0 auto;
        }

        .tribune-item {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .tribune-item h2 {
            font-size: 1.5rem;
            font-weight: bold;
            color: {{ $primaryColor }};
        }

        .tribune-item .price {
            font-weight: bold;
            font-size: 1.25rem;
            color: {{ $secondaryColor }};
        }

        .tribune-item hr {
            margin: 10px 0;
            border: none;
            border-top: 1px solid #ccc;
        }

        .quantity-controls {
            display: flex;
            align-items: center;
            margin-top: 10px;
            gap: 10px;
        }

        .quantity-controls button {
            background-color: {{ $primaryColor }};
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            font-size: 18px;
            font-weight: bold;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        .quantity-controls button:hover {
            background-color: {{ $secondaryColor }};
        }

        .quantity-controls span {
            margin: 0 10px;
            font-size: 18px;
            font-weight: bold;
        }

        .total-section {
            margin-top: 40px;
            text-align: center;
            max-width: 300px;
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin: 0 auto;
        }

        .total-price {
            font-size: 24px;
            font-weight: bold;
            color: {{ $secondaryColor }};
            margin-bottom: 20px;
        }

        .total-individual {
            font-size: 18px;
            font-weight: bold;
            color: {{ $secondaryColor }};
        }

        .checkout-button {
            display: inline-block;
            background-color: {{ $primaryColor }};
            color: white;
            font-size: 18px;
            font-weight: bold;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .checkout-button:hover {
            background-color: {{ $secondaryColor }};
        }

        .checkout-button[disabled] {
            background-color: #ccc;
            cursor: not-allowed;
        }

        .location-info {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            font-size: 18px;
            font-weight: bold;
            color: {{ $primaryColor }};
        }

        .location-info i {
            margin-right: 8px;
            font-size: 20px;
            color: {{ $secondaryColor }};
        }

        .edit-icon {
            cursor: pointer;
            font-size: 1rem;
            color: {{ $primaryColor }};
            transition: color 0.3s;
        }

        .edit-icon:hover {
            color: {{ $secondaryColor }};
        }

        .delete-icon {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 1rem;
            color: red;
            cursor: pointer;
            transition: color 0.3s;
        }

        .delete-icon:hover {
            color: darkred;
        }

        /* Responsive adjustments */
        @media (min-width: 768px) {
            .tribune-list {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</head>
<body class="bg-gray-100" @if($backgroundImage) style="background: url('{{ asset('storage/' . $backgroundImage->image_path) }}') no-repeat center center fixed; background-size: cover;" @endif>
    <x-navbar />

    <header class="text-center my-12">
        <x-page-title 
            :subtitle="__('messages.fanshop_subtitle')">
            {{ __('messages.fanshop') }}
        </x-page-title>

        @auth
            <x-button 
                route="{{ route('tribunes.create') }}" 
                buttonText="{{ __('messages.add_tribune') }}" 
                primaryColor="#DC2626" 
                secondaryColor="#B91C1C" 
            />
        @endauth
    </header>

    <div class="container mx-auto py-12">
        <div class="fanshop-container">
            @if($tribunes->isNotEmpty())
                <!-- Image du plan des tribunes -->
                @if($tribunes->first()->photo)
                    <div class="stadium-plan" data-aos="zoom-in">
                        <li class="location-info">
                            <img src="{{ asset('position.png') }}" alt="{{ __('messages.matches_played_at') }}" class="h-6 w-6 "> 
                            <span>{{ __('messages.matches_played_at') }} {{ $clubLocation }}</span>
                        </li>
                        <img src="{{ asset('storage/' . $tribunes->first()->photo) }}" alt="{{ __('messages.stadium_plan') }}" class="w-full h-auto rounded-lg shadow-lg">
                    </div>
                @endif

                <!-- Liste des tribunes -->
                <div class="tribune-list" data-aos="fade-right">
                    @foreach($tribunes as $tribune)
                        <div class="tribune-item">
                            <!-- X pour supprimer, placé en haut à droite -->
                            @auth
                            <form action="{{ route('tribunes.destroy', $tribune->id) }}" method="POST" class="delete-icon" onsubmit="return confirm('{{ __('messages.delete_confirmation') }}');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background: none; border: none; padding: 0;">
                                    ❌
                                </button>
                            </form>
                            @endauth

                            <!-- Titre de la tribune avec l'icône d'édition -->
                            <h2>
                                {{ $tribune->name }} — {{ $championship->season ?? 'N/A' }} 
                                @auth
                                <a href="{{ route('tribunes.edit', $tribune->id) }}" class="edit-icon">🛠️</a>
                                @endauth
                            </h2>
                            <p>{{ $tribune->description }}</p>

                            <!-- Affichage des détails du match à venir -->
                            @if($nextGame)
                                <div class="next-game-info">
                                    <p><strong>{{ __('messages.next_match') }}</strong> 
                                    {{ $nextGame->homeTeam->name }} vs {{ $nextGame->awayTeam->name }} 
                                    on {{ \Carbon\Carbon::parse($nextGame->match_date)->format('d-m-Y') }}</p>
                                </div>
                            @else
                                <p>{{ __('messages.no_upcoming_matches') }}</p>
                            @endif

                            <!-- Affichage du prix -->
                            <div class="price">
                                @if($tribune->price == 0)
                                    {{ __('messages.free_ticket') }}
                                @else
                                    {{ number_format($tribune->price, 2) }} {{ $tribune->currency }}
                                @endif
                            </div>
                            <hr>

                            <!-- Gestion de la quantité -->
                            @if($tribune->available_seats > 0)
                                @auth
                                    <p>{{ $tribune->available_seats }} {{ __('messages.seats_left') }}</p>
                                @endauth
                                <div class="quantity-controls">
                                    <button onclick="changeQuantity(this, {{ $tribune->price }}, {{ $tribune->available_seats }}, {{ $tribune->id }}, '{{ $tribune->currency }}')">-</button>
                                    <span id="quantity-{{ $tribune->id }}">0</span>
                                    <span class="total-individual" id="total-{{ $tribune->id }}">0.00 {{ $tribune->currency }}</span>
                                    <button onclick="changeQuantity(this, {{ $tribune->price }}, {{ $tribune->available_seats }}, {{ $tribune->id }})">+</button>
                                    <form action="{{ route('checkout') }}" method="POST" class="inline-block ml-4" id="checkout-form-{{ $tribune->id }}">
    @csrf
    <input type="hidden" name="tribune_id" value="{{ $tribune->id }}">
    <input type="hidden" name="tribune_name" value="{{ $tribune->name }}">
    <input type="hidden" name="total_amount" id="totalAmountInput-{{ $tribune->id }}" value="0">
    <input type="hidden" name="quantity" id="quantityInput-{{ $tribune->id }}" value="0">
    <button type="submit" class="checkout-button ml-4" id="checkout-button-{{ $tribune->id }}" disabled>{{ __('messages.pay') }}</button>
</form>
                                </div>
                            @else
                                <p class="text-red-500 font-bold">{{ __('messages.sold_out') }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Message d'indisponibilité s'il n'y a pas de tribunes -->
<div class="text-center text-red-500 font-bold text-lg">
    {{ __('messages.ticket_unavailable') }}
</div>
            @endif
        </div>
    </div>

    <x-footer />
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        function checkUserLocation(tribuneId) {
    // Vérifier si le navigateur supporte la géolocalisation
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            successCallback(position, tribuneId);
        }, errorCallback);
    } else {
        alert("{{ __('Geolocation is not supported by this browser.') }}");
    }
}

// Fonction appelée si la géolocalisation est réussie
function successCallback(position, tribuneId) {
    const latitude = position.coords.latitude;
    const longitude = position.coords.longitude;

    console.log("Latitude:", latitude, "Longitude:", longitude);

    // Vérification pour s'assurer que l'utilisateur est localisé au Maroc
    // Plage de latitude et longitude pour le Maroc
    if (latitude >= 21 && latitude <= 36 && longitude >= -17 && longitude <= -1) {
        console.log("{{ __('User located in Morocco. Submitting the form.') }}");
        document.getElementById('checkout-form-' + tribuneId).submit();
    } else {
        alert("{{ __('You must be located in Morocco to purchase tickets.') }}");
        window.location.href = "/fanshop"; // Rediriger vers la page fanshop
    }
}

// Fonction appelée si la géolocalisation échoue
function errorCallback(error) {
    alert("{{ __('Error retrieving location. Make sure you have allowed access to your location.') }}");
}

// Ajout d'un écouteur d'événement sur chaque bouton de soumission pour vérifier la localisation
document.querySelectorAll('.checkout-button').forEach(button => {
    button.addEventListener('click', function (event) {
        event.preventDefault();
        const tribuneId = button.id.split('-').pop(); // Récupérer l'ID de la tribune
        checkUserLocation(tribuneId); // Appeler la fonction de vérification de la localisation
    });
});

function changeQuantity(button, price, availableSeats, tribuneId, currency) {
    const quantityElement = document.getElementById('quantity-' + tribuneId);
    const totalElement = document.getElementById('total-' + tribuneId);
    const quantityInput = document.getElementById('quantityInput-' + tribuneId);
    const totalAmountInput = document.getElementById('totalAmountInput-' + tribuneId);

    let quantity = parseInt(quantityElement.innerText);
    let total = parseFloat(totalElement.innerText);

    // Vérifier si l'élément total contient déjà une devise, et si oui, la conserver
    const currentTotalText = totalElement.innerText.trim();
    const existingCurrency = currentTotalText.split(' ').pop(); // Récupérer la devise existante
    currency = existingCurrency || currency; // Utiliser la devise existante ou la devise fournie

    if (button.innerText === '+') {
        if (quantity < availableSeats) {
            quantity++;
            total += price;
        }
    } else if (button.innerText === '-') {
        if (quantity > 0) {
            quantity--;
            total -= price;
        }
    }

    quantityElement.innerText = quantity;
    totalElement.innerText = total.toFixed(2) + ' ' + currency;

    // Mettre à jour les champs cachés
    quantityInput.value = quantity;
    totalAmountInput.value = total.toFixed(2);

    const checkoutButton = document.getElementById('checkout-button-' + tribuneId);
    if (quantity > 0) {
        checkoutButton.removeAttribute('disabled');
    } else {
        checkoutButton.setAttribute('disabled', 'disabled');
    }
}

    </script>
</body>
</html>
