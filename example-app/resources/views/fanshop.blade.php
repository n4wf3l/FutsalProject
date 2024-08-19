<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fanshop | {{ $clubName }}</title>
    @if($logoPath)
        <link rel="icon" href="{{ $logoPath }}" type="image/png">
    @endif
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @vite('resources/css/app.css')
    <style>
        .fanshop-container {
            display: flex;
            justify-content: center; /* Centre le conteneur horizontalement */
            align-items: center; /* Centre le contenu verticalement */
            gap: 20px;
        }

        .stadium-plan {
            flex: 0 0 50%; /* L'image occupe 30% de la largeur totale */
            max-width: 50%;
            align-self: center; /* Centre l'image verticalement dans le conteneur */
        }

        .tribune-list {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center; /* Centre les tribunes verticalement */
            gap: 20px; /* Espacement entre les tribunes */
            margin-left: 20px;
        }

        .tribune-item {
            margin-bottom: 20px;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
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

        .edit-delete-buttons {
            display: flex;
            gap: 10px;
            margin-top: 10px;
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
    </style>
</head>
<body class="bg-gray-100">
    <x-navbar />

    <header class="text-center my-12" style="margin-top: 20px; font-size:60px;">
        <h1 class="text-6xl font-bold text-gray-900">Fanshop</h1>
        <div class="flex justify-center items-center mt-4">
        @if($nextGame)
            <p class="text-xl text-gray-600">Book your tickets for the upcoming match against {{ $nextGame->awayTeam->name }}.</p>
        @else
            <p class="text-xl text-gray-600">No upcoming home matches available.</p>
        @endif
    </div>
        @auth
        <a href="{{ route('tribunes.create') }}" class="checkout-button">
            Add Tribune
        </a>
        @endauth
    </header>

    <div class="container mx-auto py-12">

        <div class="fanshop-container">
            <!-- Image du plan des tribunes -->
            @if($tribunes->isNotEmpty() && $tribunes->first()->photo)
                <div class="stadium-plan">
                    <li class="location-info">
                        <img src="{{ asset('position.png') }}" alt="Position" class="h-6 w-6 mr-2"> 
                        <span>Matches are played at {{ $clubLocation }}</span>
                    </li>
                    <img src="{{ asset('storage/' . $tribunes->first()->photo) }}" alt="Stadium Plan" class="w-full h-auto rounded-lg shadow-lg">
                </div>
            @endif

            <!-- Liste des tribunes -->
            <div class="tribune-list">
            @foreach($tribunes as $tribune)
            <div class="tribune-item">
    <h2>{{ $tribune->name }}</h2>
    <p>{{ $tribune->description }}</p>
    
    @if($nextGame)
        <div class="next-game-info">
            <p><strong>Next Match:</strong> 
            {{ $nextGame->homeTeam->name }} vs {{ $nextGame->awayTeam->name }} 
            on {{ \Carbon\Carbon::parse($nextGame->match_date)->format('d-m-Y') }}</p>
        </div>
    @else
        <p>No upcoming matches scheduled.</p>
    @endif

    <div class="price">
        @if($tribune->price == 0)
            Free Ticket
        @else
            {{ number_format($tribune->price, 2) }} {{ $tribune->currency }}
        @endif
    </div>
    <hr>

    @if($tribune->available_seats > 0)
        @auth
            <p>{{ $tribune->available_seats }} seats left</p>
        @endauth
        <div class="quantity-controls">
        <button onclick="changeQuantity(this, {{ $tribune->price }}, {{ $tribune->available_seats }}, {{ $tribune->id }})">-</button>
    <span id="quantity-{{ $tribune->id }}">0</span>
    <span class="total-individual" id="total-{{ $tribune->id }}">0.00 {{ $tribune->currency }}</span>
    <button onclick="changeQuantity(this, {{ $tribune->price }}, {{ $tribune->available_seats }}, {{ $tribune->id }})">+</button>
    <form action="{{ route('checkout') }}" method="POST" class="inline-block ml-4">
        @csrf
        <input type="hidden" name="tribune_id" value="{{ $tribune->id }}">
        <input type="hidden" name="tribune_name" value="{{ $tribune->name }}">
        <input type="hidden" name="total_amount" id="totalAmountInput-{{ $tribune->id }}" value="0">
        <input type="hidden" name="quantity" id="quantityInput-{{ $tribune->id }}" value="0">
        <button type="submit" class="checkout-button ml-4" id="checkout-button-{{ $tribune->id }}" disabled>Payer</button>
    </form>
        </div>
    @else
        <p class="text-red-500 font-bold">Sold Out</p>
    @endif

    @auth
    <div class="edit-delete-buttons mt-4">
        <a href="{{ route('tribunes.edit', $tribune->id) }}" class="text-white font-bold py-2 px-4 rounded transition duration-200 shadow-lg" style="background-color: {{ $primaryColor }};">Edit</a>
        <form action="{{ route('tribunes.destroy', $tribune->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this tribune?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-white font-bold py-2 px-4 rounded transition duration-200 shadow-lg" style="background-color: #DC2626;">Delete</button>
        </form>
    </div>
    @endauth
</div>
            @endforeach
            </div>
        </div>
    </div>

    <x-footer />
    <script src="https://js.stripe.com/v3/"></script>
    <script>
      function changeQuantity(button, price, availableSeats, tribuneId) {
    const quantityElement = document.getElementById('quantity-' + tribuneId);
    const totalElement = document.getElementById('total-' + tribuneId);
    const quantityInput = document.getElementById('quantityInput-' + tribuneId);
    const totalAmountInput = document.getElementById('totalAmountInput-' + tribuneId);

    let quantity = parseInt(quantityElement.innerText);
    let total = parseFloat(totalElement.innerText);

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
    totalElement.innerText = total.toFixed(2) + ' ' + '{{ $tribune->currency }}';

    // Update the hidden input fields
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
