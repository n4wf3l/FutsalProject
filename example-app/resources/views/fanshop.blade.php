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
            justify-content: space-between;
            align-items: flex-start;
            gap: 20px;
        }

        .stadium-plan {
            flex: 0 0 40%;
            max-width: 40%;
        }

        .tribune-list {
            flex: 1;
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
            max-width: 300px; /* Largeur du conteneur */
            background-color: #f9f9f9; /* Couleur de fond douce */
            padding: 20px; /* Espace interne pour aérer le contenu */
            border-radius: 8px; /* Coins arrondis pour un aspect plus doux */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Ombre douce pour donner de la profondeur */
            margin: 0 auto; /* Centrer le conteneur */
        }

        .total-price {
            font-size: 24px;
            font-weight: bold;
            color: {{ $secondaryColor }};
            margin-bottom: 20px;
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
            <p class="text-xl text-gray-600">Discover additional information by hovering with your mouse.</p>
        </div>
        @auth
        <a href="{{ route('tribunes.create') }}" class="text-white font-bold py-2 px-4 rounded transition duration-200 shadow-lg mb-6 inline-block" style="background-color: {{ $primaryColor }}; font-size: 20px;">
            Add Tribune
        </a>
        @endauth
    </header>


    <div class="container mx-auto py-12">

        <div class="total-section" style="margin-bottom:50px;">
            <div class="total-price">
                Total: <span id="totalAmount">0.00</span> {{ $tribunes->first()->currency ?? '€' }}
            </div>
            <form action="{{ route('checkout') }}" method="POST" >
    @csrf
    <input type="hidden" name="total_amount" id="totalAmountInput" value="0">
    <input type="hidden" name="tribune_name" id="tribuneNameInput" value="">

    <button type="submit" class="checkout-button">
        Payer
    </button>
</form>
        </div>

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
                        <div class="price">{{ number_format($tribune->price, 2) }} {{ $tribune->currency }}</div>
                        <hr>

                        <div class="quantity-controls">
                            <button onclick="changeQuantity(this, {{ $tribune->price }})">-</button>
                            <span>0</span>
                            <button onclick="changeQuantity(this, {{ $tribune->price }})">+</button>
                        </div>

                        @auth
                        <div class="edit-delete-buttons">
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
        let total = 0;
        const totalElement = document.getElementById('totalAmount');

        function changeQuantity(button, price) {
            const quantityElement = button.parentElement.querySelector('span');
            let quantity = parseInt(quantityElement.innerText);

            if (button.innerText === '+') {
                quantity++;
                total += price;
            } else if (button.innerText === '-') {
                if (quantity > 0) {
                    quantity--;
                    total -= price;
                }
            }

            quantityElement.innerText = quantity;
            totalElement.innerText = total.toFixed(2);
        }

        document.querySelector('.checkout-button').addEventListener('click', function(e) {
        const totalAmount = document.getElementById('totalAmount').innerText;
        const tribuneName = "Your Tribune Name"; // Replace with the actual name or pass dynamically

        document.getElementById('totalAmountInput').value = parseFloat(totalAmount) * 100; // Convert to cents
        document.getElementById('tribuneNameInput').value = tribuneName;
    });
    </script>
</body>
</html>
