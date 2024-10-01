<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@lang('messages.about') | {{ $clubName }}</title>
    @if($logoPath)
        <link rel="icon" href="{{ $logoPath }}" type="image/png"> <!-- Type de l'image selon le type du logo -->
    @endif
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
     integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
     crossorigin=""/>

<!-- Meta Tags for SEO -->
<meta name="description" content="@lang('messages.about') - {{ $clubName }}. Learn more about our history, values, and latest news.">
<meta name="keywords" content="futsal, {{ $clubName }}, history, team, sports">
<meta property="og:title" content="@lang('messages.about') - {{ $clubName }}">
<meta property="og:description" content="@lang('messages.about') - Discover our history, values, and commitments.">
<meta property="og:url" content="{{ url()->current() }}">
<meta name="robots" content="index, follow">
<link rel="canonical" href="{{ url()->current() }}">

    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    <style>
        /* Styles existants */
      

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
    <x-page-title :subtitle="__('messages.learn_more_about_us')">
    {{ __('messages.about') }}
</x-page-title>
    </header>
    
    <div class="container mx-auto py-12">
        <div class="section-content">
            <div class="club-info" data-aos="fade-down-right">
                <h2>{{ $clubName }}</h2>
                <p>
                    {{ $clubName }} @lang('messages.club_description')
                </p>

            <hr>


            @foreach($sections as $section)
                <div class="" data-aos="fade-down-left">
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
                            onsubmit="return confirm('@lang('messages.confirm_delete')');">
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

                <hr style="margin-top:50px; margin-bottom:50px;">
            @endforeach

            @auth
            <div class="text-center mt-8">
                <a href="{{ route('about.create') }}"
                    class="text-white font-bold py-2 px-6 rounded-full transition duration-200 shadow-lg"
                    style="background-color: {{ $primaryColor }};"
                    onmouseover="this.style.backgroundColor='{{ $secondaryColor }}'"
                    onmouseout="this.style.backgroundColor='{{ $primaryColor }}'">
                    @lang('messages.add_new_section')
                </a>
            </div>
            @endauth
        </div>

        <div id="documents-section"></div>

        <x-page-title :subtitle="__('messages.access_club_resources')">
    {{ __('messages.documents') }}
</x-page-title>

        
        <!-- PDF List Section -->
@if($regulations->isEmpty())
    <p class="text-center text-gray-600">{{ __('messages.no_documents_available') }}</p>
@else
    <div class="regulations-list grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 mb-8 justify-center mt-20">
        @foreach($regulations as $regulation)
            <div class="regulation-item p-4 bg-white border border-gray-200 rounded-lg shadow-sm relative">
                <!-- Delete Button -->
                @auth
                <form action="{{ route('regulations.destroy', $regulation->id) }}" method="POST" onsubmit="return confirm('@lang('messages.confirm_delete_pdf')');" class="absolute top-2 right-2">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-500 hover:text-red-700 text-lg">X</button>
                </form>
                @endauth
                <h4 class="text-lg font-semibold text-gray-800 mb-2 text-center">📄 {{ $regulation->title }}</h4>
                <a href="{{ asset('storage/' . $regulation->pdf_path) }}" target="_blank" class="inline-block w-full py-2 px-4 rounded text-center text-white transition duration-200" style="background-color: {{ $primaryColor }};" onmouseover="this.style.backgroundColor='{{ $secondaryColor }}'" onmouseout="this.style.backgroundColor='{{ $primaryColor }}'">@lang('messages.view_pdf')</a>
            </div>
        @endforeach
    </div>
@endif

        <!-- Section d'Upload de PDF (pour les utilisateurs authentifiés) -->
        @auth
        <div class="upload-pdf-container mb-8 p-6 bg-white border border-gray-200 rounded-lg shadow-sm" style="max-width: 600px; margin: 0 auto;">
            <h3 class="text-lg font-bold mb-4 text-center">@lang('messages.upload_new_pdf')</h3>
            <form action="{{ route('regulations.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col items-center">
                @csrf
                <div class="form-group mb-4 w-full">
                    <label for="title" class="block text-sm font-medium text-gray-700">@lang('messages.pdf_title')</label>
                    <input type="text" name="title" id="title" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                </div>

                <div class="form-group mb-4 w-full">
                    <label for="pdf" class="block text-sm font-medium text-gray-700">@lang('messages.select_pdf')</label>
                    <input type="file" name="pdf" id="pdf" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:font-semibold" style="color: {{ $primaryColor }};" required>
                </div>

                <button type="submit" class="w-full py-2 px-4 rounded text-white transition duration-200" style="background-color: {{ $primaryColor }};" onmouseover="this.style.backgroundColor='{{ $secondaryColor }}'" onmouseout="this.style.backgroundColor='{{ $primaryColor }}'">@lang('messages.publish')</button>
            </form>
        </div>
        @endauth
        
        <div id="map" data-aos="flip-down"></div>
    </div>
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

     document.addEventListener("DOMContentLoaded", function() {
        var oembeds = document.querySelectorAll('oembed[url]');
        oembeds.forEach(function(oembed) {
            var iframe = document.createElement('iframe');

            // Set the iframe attributes to match the oEmbed's URL
            iframe.setAttribute('width', '100%');
            iframe.setAttribute('height', '315');
            iframe.setAttribute('src', oembed.getAttribute('url').replace('watch?v=', 'embed/'));
            iframe.setAttribute('frameborder', '0');
            iframe.setAttribute('allowfullscreen', 'true');

            // Replace the <oembed> element with the new iframe
            oembed.parentNode.replaceChild(iframe, oembed);
        });
    });

</script>

</body>

</html>
