<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Page</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
     integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
     crossorigin=""/>
    @vite('resources/css/app.css')
    <style>
        /* Styles existants */
        .header-section {
            text-align: center;
            margin-bottom: 3rem; 
        }

        .header-section h1 {
            font-size: 3rem; 
            font-weight: bold;
            margin-bottom: 1.5rem; 
            color: {{ $primaryColor }};
        }

        .header-section p {
            font-size: 1.25rem;
            color: #555;
            margin-bottom: 2.5rem; 
        }

        .section-content {
            padding: 3rem 2rem; 
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            font-size: 2rem; 
            font-weight: bold;
            margin-bottom: 1.5rem; 
            color: {{ $primaryColor }};
            position: relative;
            padding-left: 20px; 
        }

        h2::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 5px; 
            background-color: {{ $primaryColor }};
            border-radius: 2px; 
        }

        p {
            font-size: 1rem;
            margin-bottom: 1.5rem; 
            line-height: 1.75;
            color: #555;
        }

        .section-divider {
            border-top: 1px solid #e5e5e5;
            margin: 3rem 0; 
        }

        .club-info {
            display: flex;
            flex-direction: column; /* Passer en colonne pour pouvoir ajouter la carte sous le texte */
            margin-bottom: 3rem; 
        }

        .club-info h2 {
            font-size: 2.5rem;
            font-weight: bold;
            color: {{ $primaryColor }};
            margin-right: 2rem;
        }

        .club-info p {
            font-size: 1.25rem;
            color: #555;
            flex: 1;
        }

        /* Styles pour la carte */
        #map {
            width: 100%;
            height: 400px;
            margin-top: 20px;
            border-radius: 0.5rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Nouveau style pour les boutons */
        .button-group {
            display: flex;
            justify-content: center; 
            gap: 10px;
            margin-top: 20px;
        }

        .button-group a,
        .button-group form {
            display: inline-block;
        }
    </style>
</head>

<body class="bg-gray-100">

    <!-- Include the Navbar component -->
    <x-navbar />

    <div class="container mx-auto py-12">
        <div class="header-section">
            <h1>Clubinfo</h1>
            <p>{{ $clubName }} you're not here for a short time, but for life!</p>
        </div>

        <div class="section-content">
            <div class="club-info">
                <h2>{{ $clubName }}</h2>
                <p>
                {{ $clubName }} is a futsal club with a clear and healthy vision, 
                aiming to deliver attractive top-level futsal with as many homegrown players and/or people as possible.
                </p>

                <!-- Ajout de la carte ici -->
                <div id="map"></div>
            </div>

            <div class="section-divider"></div>

            @foreach($sections as $section)
            <div class="mb-8">
                <h2>{{ $section->title }}</h2>
                <div class="mt-4 text-gray-700 leading-relaxed">
                    {!! $section->content !!}
                </div>
                @auth
                <div class="button-group">
                    <a href="{{ route('about.edit', $section->id) }}"
                        class="text-white font-bold py-2 px-4 rounded transition duration-200 shadow-lg"
                        style="background-color: {{ $primaryColor }};"
                        onmouseover="this.style.backgroundColor='{{ $secondaryColor }}'"
                        onmouseout="this.style.backgroundColor='{{ $primaryColor }}'">
                        Edit
                    </a>
                    <form action="{{ route('about.destroy', $section->id) }}" method="POST" class="inline-block"
                        onsubmit="return confirm('Are you sure?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="text-white font-bold py-2 px-4 rounded transition duration-200 shadow-lg"
                            style="background-color: {{ $primaryColor }};"
                            onmouseover="this.style.backgroundColor='{{ $secondaryColor }}'"
                            onmouseout="this.style.backgroundColor='{{ $primaryColor }}'">
                            Delete
                        </button>
                    </form>
                </div>
                @endauth
            </div>
            @endforeach
        </div>

        <!-- Button to add a new section -->
        @auth
        <div class="text-center mt-8">
            <a href="{{ route('about.create') }}"
                class="text-white font-bold py-2 px-6 rounded-full transition duration-200 shadow-lg"
                style="background-color: {{ $primaryColor }};"
                onmouseover="this.style.backgroundColor='{{ $secondaryColor }}'"
                onmouseout="this.style.backgroundColor='{{ $primaryColor }}'">
                Add New Section
            </a>
        </div>
        @endauth
    </div>

    <!-- Include the Footer component -->
    <x-footer />

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
     integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
     crossorigin=""></script>
     <script>
    @if($clubInfo)
        console.log('Latitude:', {{ $clubInfo->latitude }});
        console.log('Longitude:', {{ $clubInfo->longitude }});

        var map = L.map('map').setView([{{ $clubInfo->latitude }}, {{ $clubInfo->longitude }}], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        L.marker([{{ $clubInfo->latitude }}, {{ $clubInfo->longitude }}]).addTo(map)
            .bindPopup('{{ $clubInfo->sportcomplex_location }}')
            .openPopup();
    @else
        console.log('No club info available');
    @endif
</script>

</body>

</html>
