<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About | {{ $clubName }}</title>
    @if($logoPath)
        <link rel="icon" href="{{ $logoPath }}" type="image/png"> <!-- Type de l'image selon le type du logo -->
    @endif
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

        p {
            font-size: 1rem;
            margin-bottom: 0.3rem; /* Réduction de l'espace entre les paragraphes */
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

<body class="bg-gray-100" @if($backgroundImage) style="background: url('{{ asset('storage/' . $backgroundImage->image_path) }}') no-repeat center center fixed; background-size: cover;" @endif>

    <!-- Include the Navbar component -->
    <x-navbar />

    <header class="text-center my-12">
    <x-page-title subtitle="ℹ️ Learn more about who we are and what we stand for, providing you with the story behind our mission and values.">
    About
</x-page-title>
    </header>
    
    <div class="container mx-auto py-12">
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

<!-- Section d'Upload de PDF (pour les utilisateurs authentifiés) -->
@auth
<div class="upload-pdf-container mb-8 p-6 bg-white border border-gray-200 rounded-lg shadow-sm" style="max-width: 600px; margin: 0 auto;">
    <h3 class="text-lg font-bold mb-4 text-center">Uploader un nouveau PDF</h3>
    <form action="{{ route('regulations.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col items-center">
        @csrf
        <div class="form-group mb-4 w-full">
            <label for="title" class="block text-sm font-medium text-gray-700">Titre du PDF</label>
            <input type="text" name="title" id="title" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
        </div>

        <div class="form-group mb-4 w-full">
            <label for="pdf" class="block text-sm font-medium text-gray-700">Sélectionner un PDF</label>
            <input type="file" name="pdf" id="pdf" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:font-semibold" style="color: {{ $primaryColor }};" required>
        </div>

        <button type="submit" class="w-full py-2 px-4 rounded text-white transition duration-200" style="background-color: {{ $primaryColor }};" onmouseover="this.style.backgroundColor='{{ $secondaryColor }}'" onmouseout="this.style.backgroundColor='{{ $primaryColor }}'">Publier</button>
    </form>
</div>
@endauth

<!-- Section Liste des PDFs -->
<div class="regulations-list grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 mb-8 justify-center mt-20">
    @foreach($regulations as $regulation)
        <div class="regulation-item p-4 bg-white border border-gray-200 rounded-lg shadow-sm relative">
            <!-- Bouton de suppression -->
             @auth
            <form action="{{ route('regulations.destroy', $regulation->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce PDF ?');" class="absolute top-2 right-2">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-500 hover:text-red-700 text-lg">X</button>
            </form>
@endauth
            <h4 class="text-lg font-semibold text-gray-800 mb-2 text-center">📄 {{ $regulation->title }}</h4>
            <a href="{{ asset('storage/' . $regulation->pdf_path) }}" target="_blank" class="inline-block w-full py-2 px-4 rounded text-center text-white transition duration-200" style="background-color: {{ $primaryColor }};" onmouseover="this.style.backgroundColor='{{ $secondaryColor }}'" onmouseout="this.style.backgroundColor='{{ $primaryColor }}'">Voir le PDF</a>
        </div>
    @endforeach
</div>
            <div class="section-divider"></div>


            @foreach($sections as $section)
    <div class="mb-8">
        <x-page-subtitle text="{{ htmlspecialchars_decode($section->title, ENT_QUOTES) }}" />
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
                🛠️
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
                    X
                </button>
            </form>
        </div>
        @endauth
    </div>
    <hr>
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

    <hr>
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
