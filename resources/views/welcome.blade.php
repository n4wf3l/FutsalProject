@php
    $backgroundImage = \App\Models\BackgroundImage::where('assigned_page', 'welcome')->first();
@endphp
<!DOCTYPE html>
<html lang="{{ App::getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@lang('messages.welcome') | {{ $clubName }}</title>

    <!-- Favicon -->
    @if($logoPath)
        <link rel="icon" href="{{ $logoPath }}" type="image/png">
    @endif
    <!-- CSS Bootstrap pour les Modals -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- CSS App -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

<!-- Meta Tags for SEO -->
<meta name="description" content="@lang('messages.welcome_to_club') - {{ $clubName }} in {{ $city }}. Discover our latest news, matches, and more.">
<meta name="keywords" content="futsal, {{ $clubName }}, {{ $city }}, matches, sports">
<meta property="og:title" content="@lang('messages.welcome') - {{ $clubName }} in {{ $city }}">
<meta property="og:description" content="@lang('messages.welcome_to_club') - Discover our latest news, matches, and more.">
<meta property="og:image" content="{{ $backgroundImage && $backgroundImage->image_path ? asset('storage/' . $backgroundImage->image_path) : asset('storage/default-image.jpg') }}">
<meta property="og:url" content="{{ url()->current() }}">
<meta name="robots" content="index, follow">
<link rel="canonical" href="{{ url()->current() }}">
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
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
                min-height: 100%;
                @if($backgroundImage)
                    background: url('{{ asset('storage/' . $backgroundImage->image_path) }}') no-repeat center center;
                    background-size: cover;
                @else
                    background-color: #f8f9fa;
                @endif
            }

            .cover-container {
                position: relative;
                width: 100%;
                min-height: 40vh;
                background-color: #f1f1f1;
            }

            /* White container positioned at the center */

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
                font-size: 30px;
                position: relative;
                overflow: hidden;
                width: 100%;
                background-color: {{ $secondaryColor }};
                color: white;
                padding: 20px 0;
                z-index: 100;
                display: flex;
                align-items: center;
            }

            .flash-message {
                display: inline-block;
                white-space: nowrap;
                position: absolute;
                left: 100%; /* Commence en dehors de l'écran, à droite */
                animation: marquee 20s linear infinite;
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

            .carousel-container {
                display: flex;
                justify-content: center;
                align-items: center;
                overflow: hidden;
            }

            .carousel-slide {
                display: flex;
                transition: transform 0.5s ease-in-out;
            }

            .carousel-item {
                flex: 0 0 100%;
                display: flex;
                align-items: center;
                justify-content: center;
                height: 100%; /* Hauteur égale à celle du conteneur */
                position: relative;
            }

            .carousel-item img {
                object-fit: cover;
                object-position: center;
                width: 100%;
                height: 100%; /* Hauteur égale à celle du conteneur */
                border-radius: 8px;
                max-height: 500px; /* S'assure que les images ne dépassent pas la hauteur du conteneur */
            }

            .carousel-slide {
                display: flex;
                transition: transform 0.5s ease-in-out;
            }

            .carousel-item.active {
                opacity: 1;
            }

            .main-article-content {
                display: flex;
                flex-direction: column;
                align-items: flex-start;
                text-align: left;
                position: absolute;
                color: white;
                background-color: rgba(0, 0, 0, 0.5);
                padding: 20px;
                border-radius: 8px;
                margin-bottom: 15%;
                bottom: 0;
                left: 0;
                width: 100%;
            }

            .main-article-content p,
            .main-article-content h2 {
                margin: 0;
            }

            .article-title {
                font-size: 2.5rem;
                color: white;
                font-family: 'Bebas Neue', sans-serif;
                margin-bottom: 20px;
            }

            .buttons-container {
                margin-top: 30px;
                display: flex;
                justify-content: flex-start;
                gap: 20px;
            }

            .buttons-container a {
                padding: 10px 20px;
                background-color: #DC2626;
                color: white;
                font-family: 'Bebas Neue', sans-serif;
                text-transform: uppercase;
                text-decoration: none;
                border-radius: 5px;
                font-size: 1.5rem;
                transition: transform 0.3s ease;
            }

            .buttons-container a:hover {
                transform: scale(1.1);
                background-color: #B91C1C;
            }

            .carousel-controls {
                position: absolute;
                top: 50%;
                transform: translateY(-50%);
                width: 100%;
                display: flex;
                justify-content: space-between;
                padding: 0 20px;
            }

            .carousel-controls button {
                background-color: rgba(0, 0, 0, 0.5);
                border: none;
                color: white;
                padding: 10px;
                cursor: pointer;
                border-radius: 50%;
            }

            .carousel-indicators {
                position: absolute;
                bottom: 20px;
                display: flex;
                justify-content: center;
                width: 100%;
            }

            .carousel-indicators .dot {
                height: 15px;
                width: 15px;
                margin: 0 5px;
                background-color: #bbb; /* Couleur par défaut des dots */
                border-radius: 50%;
                display: inline-block;
                cursor: pointer;
            }

            .carousel-indicators .dot.active {
                background-color: red; /* Couleur pour le dot actif */
            }

            .carousel .main-article-container img {
                filter: grayscale(100%);
                transition: filter 0.5s ease;
            }

            .carousel .main-article-container.active img {
                filter: grayscale(0%);
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

        /* Nos Albums link styling */
        .albums-link-container {
            text-align: center;
            margin-top: 30px;
            margin-bottom:50px;
        }

        .albums-link {
            font-size: 1.5rem;
            color: {{ $primaryColor }};
            text-decoration: none;
            font-weight: bold;
            border: 2px solid {{ $primaryColor }};
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .albums-link:hover {
            background-color: {{ $primaryColor }};
            color: white;
            border-color: {{ $secondaryColor }};
        }

        .latest-photos .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(400px, 1fr)); /* Ensure items fill available space */
            gap: 10px;
            grid-auto-flow: dense; /* Fill gaps effectively */
            margin-bottom:50px;
        }

        /* Individual gallery item style for latest photos */
        .latest-photos .gallery-item {
            position: relative;
            overflow: hidden;
            aspect-ratio: 1; /* Ensure items are squares */
        }

        .latest-photos .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover; /* Ensures the images cover the entire area */
            display: block;
            border-radius: 8px;
            transition: transform 0.3s ease;
        }

        .latest-photos .gallery-item:hover img {
            transform: scale(1.05);
        }

        /* Delete button style for each photo (if applicable) */
        .latest-photos .delete-photo {
            position: absolute;
            top: 5px;
            right: 5px;
            background-color: rgba(255, 0, 0, 0.7);
            color: white;
            border: none;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 10;
        }

        /* Modal overlay style */
        .latest-photos .modal {
            display: none;
            position: fixed;
            z-index: 1200;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.8);
            justify-content: center;
            align-items: center;
        }

        /* Modal content style */
        .latest-photos .modal-content-wrapper {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            height: 100%;
        }

        /* Close button style for modals */
        .latest-photos .close {
            position: absolute;
            top: 20px;
            right: 30px;
            color: #fff;
            font-size: 40px;
            font-weight: bold;
            cursor: pointer;
            z-index: 400; /* Ensure it is above other elements */
        }

        .latest-photos .close:hover,
        .latest-photos .close:focus {
            color: #bbb;
            text-decoration: none;
            cursor: pointer;
        }

        /* Modal content style */
        .latest-photos .modal-content {
            max-width: 90%;
            max-height: 90%;
            margin: auto;
            display: block;
        }

        /* Navigation buttons for image modal */
        .latest-photos .prev,
        .latest-photos .next {
            cursor: pointer;
            position: absolute;
            top: 50%;
            padding: 16px;
            color: white;
            font-weight: bold;
            font-size: 20px;
            transition: 0.6s ease;
            user-select: none;
            transform: translateY(-50%);
            z-index: 400; /* Ensure buttons are above images but below the close button */
        }

        .latest-photos .prev {
            left: 10px;
            border-radius: 0 3px 3px 0;
        }

        .latest-photos .next {
            right: 10px;
            border-radius: 3px 0 0 3px;
        }

        .latest-photos .prev:hover,
        .latest-photos .next:hover {
            background-color: rgba(0, 0, 0, 0.8);
        }

        /* Preview image styles for upload modal */
        .latest-photos .preview-image {
            max-width: 100px;
            max-height: 100px;
            object-fit: cover;
            margin-right: 10px;
            margin-bottom: 10px;
        }

        .latest-photos .photo-item {
            margin-bottom: 1rem;
        }

        .latest-photos .image-caption {
            position: absolute;
            bottom: 10px;
            right: 10px;
            color: white;
            background-color: rgba(0, 0, 0, 0.5);
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 12px;
            font-weight: bold;
        }

        modal-content {
            max-width: 90%;
            max-height: 90%;
            margin: auto;
            display: block;
            object-fit: contain; /* Assure que l'image conserve ses proportions sans être déformée */
        }

        .modal-background-dark {
    background-color: rgba(0, 0, 0, 0.8); /* Couleur de fond sombre */
}
        /* Ajouter ces propriétés pour gérer l'image dans la modal */
        .modal-content-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            height: 100%;
            position: relative;
        }
        .modal-content img {
    filter: none; /* Pas d'effet sur l'image sélectionnée */
}

        .modal-content {
            width: auto; /* Permet à l'image de s'adapter à sa taille d'origine */
            height: auto; /* Permet à l'image de s'adapter à sa taille d'origine */
            max-width: 90%; /* Limite la largeur maximale à 90% de la modal */
            max-height: 90%; /* Limite la hauteur maximale à 90% de la modal */
            object-fit: contain;
        }

        /* Close button style for modals */
        .close {
            position: absolute;
            top: 20px;
            right: 30px;
            color: #fff;
            font-size: 40px;
            font-weight: bold;
            cursor: pointer;
            z-index: 400; /* Ensure it is above other elements */
        }

        .close:hover,
        .close:focus {
            color: #bbb;
            text-decoration: none;
            cursor: pointer;
        }

        /* Navigation buttons for image modal */
        .prev,
        .next {
            cursor: pointer;
            position: absolute;
            top: 50%;
            padding: 16px;
            color: white;
            font-weight: bold;
            font-size: 20px;
            transition: 0.6s ease;
            user-select: none;
            transform: translateY(-50%);
            z-index: 400; /* Ensure buttons are above images but below the close button */
        }

        .prev {
            left: 10px;
            border-radius: 0 3px 3px 0;
        }

        .next {
            right: 10px;
            border-radius: 3px 0 0 3px;
        }

        .prev:hover,
        .next:hover {
            background-color: rgba(0, 0, 0, 0.8);
        }

        /* Caption style */
        .image-caption {
            position: absolute;
            bottom: 10px;
            right: 10px;
            color: white;
            background-color: rgba(0, 0, 0, 0.5);
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 12px;
            font-weight: bold;
            z-index: 300; /* Ensure caption is above image */
        }

        .main-article-image img {
    transition: transform 0.8s cubic-bezier(0.25, 0.1, 0.25, 1); /* Transition plus lente et fluide avec une courbe cubic-bezier */
}

.main-article-container:hover .main-article-image img {
    transform: scale(1.01); /* Zoom très léger de 101% pour un effet à peine perceptible */
}

        .video-item {
                position: relative;
                display: block;
                overflow: hidden;
                width: 100%;
                transition: transform 0.3s ease; /* Ajoute une transition pour l'effet de zoom */
            }

            .video-item:hover {
                transform: scale(1.05); /* Effet de zoom au survol */
            }

            .video-item img {
                width: 100%;
                height: auto;
                object-fit: cover;
                display: block;
            }

            /* Nouveau conteneur pour centrer l'icône */
            .play-icon-container {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 80%;
                display: flex;
                justify-content: center;
                align-items: center;
                pointer-events: none;
            }

            .play-icon {
                width: 90px; /* Taille du cercle */
                height: 90px; /* Taille du cercle */
                background-color: {{ $primaryColor }};
                border-radius: 50%;
                display: flex;
                justify-content: center;
                align-items: center;
                transition: background-color 0.3s ease;
            }

            .play-icon::before {
                content: '';
                display: block;
                width: 0;
                height: 0;
                border-left: 24px solid white; /* Triangle "play" */
                border-top: 12px solid transparent;
                border-bottom: 12px solid transparent;
                transition: border-left-color 0.3s ease;
            }

            .video-item:hover .play-icon::before {
                border-left-color: {{ $secondaryColor }}; /* Change la couleur du triangle au survol */
            }

                    /* Effet d'écriture avec curseur clignotant */
        #typing-text {
            white-space: nowrap;
        }

        @keyframes cursor-blink {
            0% { border-right-color: rgba(255, 255, 255, 0.75); }
            50% { border-right-color: transparent; }
            100% { border-right-color: rgba(255, 255, 255, 0.75); }
        }

        /* Style du bouton */
        #reserve-button {
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

        @media (max-width: 768px) {
            #typing-text {
                display: none; /* Masque le texte de saisie */
            }

            .welcome-image {
                display: none; /* Masque l'image de bienvenue */
            }

            .flash-message-container {
                display: none; /* Masque l'image de bienvenue */
            }

            .info-container {

            }

            .image-caption {
          display: none;
      }

            .latest-photos .gallery-grid {
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
                width: 100%; /* Ensure it doesn't exceed the container width */
                max-width: 100%; /* Prevent overflow */
                overflow-x: hidden; /* Hide any possible overflow */
            }

            .gallery-item img {
                max-width: 100%;
                width: 100%; /* Ensure the image doesn't exceed the container */
                height: auto;
            }

            .main-article-content {
                width: 100%;
                max-width: 100%; /* Ensure content doesn't exceed container width */
                overflow-x: hidden;
            }

            /* Any other container that might exceed the width */
            .carousel-container,
            .info-container,
            .main-article-container,
            .gallery-item {

            }

            .containerization {
                max-width: 100%; /* Ensure it doesn't exceed the screen width */
                width: 100%;
            }
        }

        @media (max-width: 768px) {
            .carousel-container {
                width: 100% !important;
                overflow: hidden !important;
            }

            .main-article-container {
                position: relative !important;
                width: 100% !important;
                height: auto !important;
            }

            .main-article-image img {
                width: 100% !important;
                height: auto !important;
                object-fit: cover !important;
            }

            .main-article-content {
                position: absolute !important;
                bottom: 0 !important;
                left: 0 !important;
                width: 100% !important;
                padding: 10px !important;
                background-color: rgba(0, 0, 0, 0.7) !important;
                color: white !important;
                display: flex !important;
                justify-content: flex-start !important;
                align-items: center !important;
                flex-direction: row !important;
                box-sizing: border-box !important;
            }

            .main-article-content .news-badge {
                display: block !important;
                background-color: {{ $primaryColor }} !important;
                color: #ffffff !important;
                font-size: 0.75rem !important;
                text-transform: uppercase !important;
                font-weight: bold !important;
                padding: 4px 8px !important;
                border-radius: 4px !important;
                margin-right: 10px !important;
                flex-shrink: 0 !important;
            }

            .main-article-content h2 {
                font-size: 0.9rem !important;
                margin: 0 !important;
                color: white !important;
                white-space: nowrap !important; /* Empêche le texte de se répartir sur plusieurs lignes */
                overflow: hidden !important; /* Cache le texte qui dépasse la largeur du conteneur */
                text-overflow: ellipsis !important; /* Ajoute les points de suspension si le texte est trop long */
                background-color: transparent !important;
                color: white !important;
                width: 100%; /* Occupe toute la largeur disponible */
            }

            /* Hide the "Read More" button and other elements */
            .buttons-container,
            .main-article-content p:not(.news-badge),
            .carousel-controls,
            .carousel-indicators {
                display: none !important;
            }
        }

        @media (max-width: 768px) {
            .latest-photos .gallery-grid {
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); /* Minimum 150px par colonne */
            }
        }

        @media (max-width: 480px) {
            .latest-photos .gallery-grid {
                grid-template-columns: repeat(auto-fill, minmax(100px, 1fr)); /* Minimum 100px par colonne */
            }

            .info-container{

            }
    }

    .font-bebas {
    font-family: 'Bebas Neue', sans-serif;
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
<div class="mr-2">
    {{ $city }} | 
    <span style="font-weight: bold;">
        {{ isset($weatherData['main']['temp']) ? round($weatherData['main']['temp']) : 'N/A' }}°C
    </span>
</div>
                @auth
                    <button type="button" class="btn btn-sm btn-light mr-2" data-bs-toggle="modal" data-bs-target="#editFlashMessageModal">
                        EDIT
                    </button>
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#addImageModal">
                        Add Image
                    </button>
                @endauth
            </li>
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
                        <!-- Section pour afficher l'image actuelle -->
                        @if($welcomeImage && $welcomeImage->image_path)
                            <div class="mb-3 text-center">
                                <label class="form-label">Current Image</label>
                                <div>
                                    <img src="{{ asset('storage/' . $welcomeImage->image_path) }}" alt="Current Image" class="img-fluid" style="max-height: 200px;">
                                </div>
                            </div>
                        @endif
                        <!-- Section pour sélectionner une nouvelle image -->
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
                        <div class="mb-3">
                            <label for="homemessage" class="form-label">Home Message</label>
                            <input type="text" class="form-control" id="homemessage" name="homemessage" value="{{ $flashMessage->homemessage ?? '' }}" required>
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

    <div class="background-container" 
     style="position: relative; width: 100%; height: 60vh; background: url('{{ $backgroundImage && $backgroundImage->image_path ? asset('storage/' . $backgroundImage->image_path) : asset('storage/default-background.jpg') }}') no-repeat center center; background-size: cover; z-index: 500;">
        @if($welcomeImage)
            <img src="{{ asset('storage/' . $welcomeImage->image_path) }}" alt="Welcome Image" class="welcome-image" style="position: absolute; top:18vh; right: 100px; width: 550px; height: 500px;" data-aos="fade-up-left">
        @endif
        <div id="typing-text" style="position: absolute;  left: 50%; transform: translateX(-50%); color: {{ $secondaryColor }}; font-family: 'Bebas Neue', sans-serif; font-size: 4rem; font-weight: bold; text-align: center; text-shadow: 2px 2px 5px rgba(0,0,0,0.7); z-index: 1300;">
{{ $flashMessage->homemessage ?? '' }}
        </div>

        <img id="club-logo" src="{{ $logoPath ? asset($logoPath) : '' }}" alt="Club Logo" style="display:none; width: 150px; position: absolute; left: 50%; transform: translateX(-50%); margin-top: 30vh; opacity: 0; transition: opacity 1s ease-in-out;">

        <a id="reserve-button" href="{{ route('fanshop.index') }}" style="display:none; padding: 15px 30px; color: white; font-family: 'Bebas Neue', sans-serif; font-size: 1.5rem; border: none; border-radius: 5px; cursor: pointer; text-decoration: none; position: absolute; left: 50%; transform: translateX(-50%); opacity: 0; transition: opacity 1s ease-in-out;" onmouseover="this.style.backgroundColor='{{ $primaryColor }}';"
        onmouseout="this.style.backgroundColor='{{ $secondaryColor }}';">
            @lang('messages.reserve_your_ticket')
        </a>
    </div>


<!-- Container Dernier Score -->
<section class="last-game-container">
    <div class="last-game-card">
        <h2 class="last-game-title">Last Game</h2>
        
        <!-- Localisation avec condition d'affichage -->
        <div class="location">
            <img src="{{ asset('position.png') }}" alt="Location" class="location-icon">
            @if(Str::startsWith($lastGame->homeTeam->name, $clubPrefix))
                <span>{{ $clubLocation }}, {{ $city }}</span>
            @else
                <span>@lang('messages.away')</span>
            @endif
        </div>
        
        <div class="match-details">
            <!-- Équipe Domicile -->
            <div class="team">
                <img src="{{ asset('storage/' . $lastGame->homeTeam->logo_path) }}" alt="Home Team Logo" class="team-logo" data-aos="flip-right">
                <h3 class="team-name">{{ $lastGame->homeTeam->name }}</h3>
            </div>
            
            <!-- Score -->
            <div class="score">
                <span>{{ $lastGame->home_score }} - {{ $lastGame->away_score }}</span>
                <p class="score-text">Final Score</p>
            </div>

            <!-- Équipe Visiteur -->
            <div class="team">
                <img src="{{ asset('storage/' . $lastGame->awayTeam->logo_path) }}" alt="Away Team Logo" class="team-logo" data-aos="flip-left">
                <h3 class="team-name">{{ $lastGame->awayTeam->name }}</h3>
            </div>
        </div>
        
        <!-- Date du match -->
        <div class="match-date">
            <img src="{{ asset('date.png') }}" alt="Date" class="date-icon">
            <span>{{ \Carbon\Carbon::parse($lastGame->match_date)->format('d-m-Y') }}</span>
        </div>
    </div>
</section>

<div class="albums-link-container">

<a href="{{ route('calendar.show', ['team_filter' => 'club', 'date_filter' => 'results']) }}#calendar-section" style="
       display: inline-block;
       background-color: {{ $primaryColor }};
       color: white;
       font-size: 18px;
       font-weight: bold;
       padding: 10px 20px;
       border-radius: 8px;
       cursor: pointer;
       transition: background-color 0.3s;
       font-family: 'Bebas Neue', sans-serif;
       letter-spacing: 1px;
   "
   onmouseover="this.style.backgroundColor='{{ $secondaryColor }}'"
   onmouseout="this.style.backgroundColor='{{ $primaryColor }}'">
        @lang('messages.results')
    </a>
        
        </div>
<style>
  .last-game-container {
    padding: 3rem 0;
    display: flex;
    justify-content: center;
}

.last-game-card {
    background-color: #fff;
    border: 8px solid {{ $secondaryColor }};
    border-radius: 0.5rem;
    padding: 2rem;
    width: 100%; /* Utiliser la largeur complète du conteneur */
    max-width: 70rem; /* Augmente la largeur maximum si besoin */
    min-height: 35rem; /* Augmente légèrement la hauteur pour un look plus équilibré */
    text-align: center;
    box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
    font-family: 'Bebas Neue', sans-serif;
    margin: 0 auto; /* Centre la carte */
}
/* Titre */
.last-game-title {
    font-size: 2.5rem;
    font-weight: 800;
    color: {{ $secondaryColor }};
    margin-bottom: 2rem;
    text-transform: uppercase;
    letter-spacing: 0.1em;
}

/* Localisation */
.location {
    display: flex;
    align-items: center;
    justify-content: center;
    color: #4a5568;
    font-size: 1.125rem;
    margin-bottom: 1.5rem;
}

.location-icon {
    height: 1.5rem;
    width: 1.5rem;
    margin-right: 0.25rem; /* Réduire la marge pour un alignement plus proche */
}

/* Détails du match */
.match-details {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 2rem;
    margin-bottom: 2rem;
}

/* Style de l'équipe */
.team {
    text-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

.team-logo {
    height: 8rem !important;
    width: 8rem !important;
    object-fit: cover;
    margin: 0 auto;
    display: block;
}

.team-name {
    margin-top: 20px;
    font-size: 1.5rem;
    color: #2d3748;
}

/* Score */
.score {
    font-size: 3rem;
    font-weight: bold;
    color: {{ $secondaryColor }};
}

.score-text {
    font-size: 1rem;
    color: {{ $secondaryColor }};
    margin-top: 0.5rem;
}

/* Date du match */
.match-date {
    display: flex;
    align-items: center;
    justify-content: center;
    color: #4a5568;
    font-size: 1.125rem;
}

.date-icon {
    height: 1.5rem;
    width: 1.5rem;
    margin-right: 0.25rem;
}

/* Disposition en ligne pour les écrans larges */
@media (min-width: 768px) {
    .match-details {
        flex-direction: row;
        justify-content: center;
    }

    .team-logo {
        height: 8rem;
        width: 8rem;
    }

    .score {
        font-size: 4rem;
    }

    .last-game-title {
        font-size: 3rem;
        margin-bottom: 2.5rem;
    }

    .location,
    .match-date {
        font-size: 1.25rem;
    }
}

@media (min-width: 1024px) {
    .last-game-title {
        font-size: 3.5rem;
    }

    .location,
    .match-date {
        font-size: 1.5rem;
    }

    .score {
        font-size: 5rem;
    }
}
</style>

    <hr style="margin-bottom:50px;">

    <section class="mt-20 md:mt-0">
    <div style="text-align:center;">
        <x-page-title subtitle="🔔 {{ __('messages.stay_informed') }}">
            @lang('messages.latest_news')
        </x-page-title>
    </div>

    @if($articles->isEmpty())
        <p class="text-center text-gray-600">No news has been added yet.</p>
    @else
        <div class="carousel-container" data-aos="fade-right" style="position: relative; width: 100%; display: flex; justify-content: center; align-items: center;  z-index: 1000;">
            <div class="carousel" style="display: flex; transition: transform 0.5s ease-in-out; width: 80%;">
                @foreach($articles as $index => $article)
                    <div class="main-article-container" style="flex: 0 0 100%; display: flex; flex-direction: row; align-items: flex-start; position: relative;">
                        <!-- Main Article Image -->
                        <div class="main-article-image" style="width: 100%;">
                            <a href="{{ route('articles.show', $article->slug) }}">
                                @if($article->image)
                                    <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->title }}">
                                @endif
                            </a>
                        </div>

                        <!-- Main Article Content -->
                        <div class="main-article-content" data-aos="fade-right" style="padding-left: 20px; display: flex; flex-direction: column; justify-content: center; position: absolute; bottom: 30px; left: 30px; color: white; max-width: 50%; background-color: rgba(0, 0, 0, 0.5); padding: 20px; border-radius: 8px;">
                            <p style="background-color: {{ $primaryColor }};
                        color: #ffffff;
                        font-size: 0.875rem;
                        margin-bottom: 1rem;
                        text-transform: uppercase;
                        font-weight: bold;
                        padding: 4px 8px;
                        display: inline-block;
                        border-radius: 4px;">
                                @lang('messages.news')
                            </p>
                            <h2 class="text-3xl font-bold mb-2 article-title" style="font-size: 2.5rem; margin-bottom: 20px;">
                                <strong>{{ $article->title }}</strong>
                            </h2>
                            <p class="text-sm text-white" style="font-size: 1rem; margin-bottom: 15px;">
                                @lang('messages.published_on'): {{ $article->created_at->format('d M Y, H:i') }} @lang('messages.by') {{ $article->user->name }}
                            </p>
                            <!-- Buttons Container -->
                            <div class="buttons-container" style="margin-top: 30px; display: flex; justify-content: flex-start; gap: 20px;">
                                <x-button 
                                    route="{{ route('articles.show', $article->slug) }}"
                                    buttonText="{{ __('messages.read_more') }}" 
                                    primaryColor="#B91C1C" 
                                    secondaryColor="#DC2626" 
                                />
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Navigation Dots -->
            <div class="navigation-dots" style="position: absolute; bottom: 20px; display: flex; justify-content: center; width: 100%;">
                @foreach($articles as $index => $article)
                    <div id="dot-{{ $index }}" class="dot" onclick="goToSlide({{ $index }})" style="margin: 0 5px; cursor: pointer;">
                        <span id="emoji-{{ $index }}">{{ $index === 0 ? '⚫️' : '⚪' }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="albums-link-container">
            <x-button 
                route="{{ route('articles.index') }}"
                buttonText="{{ __('messages.recent_articles') }}" 
                primaryColor="#B91C1C" 
                secondaryColor="#DC2626" 
            />
        </div>
</section>

<hr class ="mt-10" style="margin-bottom:50px;">

    <section style="width: 100%; padding: 20px 0;">
    <div style="text-align:center; margin-bottom: 20px;">
        <x-page-title style="text-align:center" subtitle="{{ __('messages.get_ready') }}">
            @lang('messages.upcoming_matches')
        </x-page-title>
    </div>

    <div class="matches-row">
        @foreach($nextGames as $index => $game)
            <div class="match-card">
                <!-- Date et Lieu -->
                <div class="match-info-top">
                    <div class="match-date">
                        {{ \Carbon\Carbon::parse($game->match_date)->format('d F Y') }}
                    </div>
                    <div class="match-location">
                        @if(Str::startsWith($game->homeTeam->name, $clubPrefix))
                            {{ $clubLocation }} (@lang('messages.home'))
                        @else
                            @lang('messages.away')
                        @endif
                    </div>
                </div>

                <!-- Logos et VS -->
                <div class="logos-row">
                    <div class="team">
                        <img src="{{ asset('storage/' . $game->homeTeam->logo_path) }}" alt="{{ $game->homeTeam->name }} Logo" class="team-logo" data-aos="fade-right">
                    </div>
                    <div class="vs">VS</div>
                    <div class="team">
                        <img src="{{ asset('storage/' . $game->awayTeam->logo_path) }}" alt="{{ $game->awayTeam->name }} Logo" class="team-logo" data-aos="fade-left">
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</section>

<div class="see-all-container">
            <x-button 
                route="{{ route('calendar.show', ['team_filter' => 'specific_team', 'date_filter' => 'upcoming']) }}#calendar-section"
                buttonText="{{ __('messages.see_all_matches') }}" 
                primaryColor="#B91C1C" 
                secondaryColor="#DC2626" 
            />
        </div>
<style>
    .matches-row {
        display: flex;
        justify-content: center;
        gap: 20px;
        padding: 0 10px;
        flex-wrap: wrap;
    }

    .match-card {
        width: 200px;
        text-align: center;
        background-color: #f9f9f9;
        border-radius: 8px;
        padding: 15px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .match-card:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    .match-info-top {
        margin-bottom: 10px;
    }

    .match-date {
        font-size: 16px;
        font-weight: bold;
        color: #333;
    }

    .match-location {
        font-size: 14px;
        color: #666;
    }

    .logos-row {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
    }

    .team-logo {
        width: 60px;
        height: auto;
        object-fit: contain;
        transition: transform 0.3s ease;
    }

    .team-logo:hover {
        transform: scale(1.1);
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .matches-row {
            flex-direction: column;
            align-items: center;
        }

        .match-card {
            width: 80%;
            max-width: 300px;
            margin-bottom: 20px;
        }
    }

    @media (max-width: 480px) {
        .match-card {
            width: 100%;
        }

        .logos-row .vs {
            font-size: 14px;
        }
    }
</style>


    <hr class ="mt-50" style="margin-bottom:100px; margin-top:100px;">

  <section class="latest-photos">
    <div class="container" style="text-align:center">
        <x-page-title subtitle="📸 {{ __('messages.explore_gallery') }}">
            @lang('messages.explore_latest_photos')
        </x-page-title>
        
        @if($latestPhotos->isEmpty())
            <p class="text-center text-gray-600">No photos have been added yet.</p>
        @else
            <div class="gallery-grid mt-10" data-aos="fade-up">
                @foreach($latestPhotos as $photo)
                    <div class="gallery-item">
                        <img src="{{ asset('storage/' . $photo->image) }}" alt="{{ $photo->caption }}" onclick="openImageModal({{ $loop->index }})">
                        @if($photo->caption)
                            <div class="image-caption">© {{ $photo->caption }}</div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Nos Albums link -->
        <div class="albums-link-container">
            <x-button 
                route="{{ route('galleries.index') }}"
                buttonText="{{ __('messages.our_albums') }}"
                primaryColor="#B91C1C" 
                secondaryColor="#DC2626" 
            />
        </div>
    </div>
</section>


<hr class ="mt-10" style="margin-bottom:50px;">

        <!-- Section des deux dernières vidéos -->
        <section class="latest-videos" data-aos="fade-right">
            <div class="container mx-auto">
                <x-page-title subtitle="{{ __('messages.check_latest_videos') }}">
                    @lang('messages.latest_videos')
                </x-page-title>
                @if($videos->isEmpty())
                    <p class="text-center text-gray-600">@lang('messages.no_videos_published')</p>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($videos as $video)
                            <div class="video-item bg-white shadow-md rounded-lg overflow-hidden">
                                <a href="{{ $video->url }}" target="_blank" class="block relative">
                                    @if($video->image)
                                        <img src="{{ asset('storage/' . $video->image) }}" alt="{{ $video->title }}" class="w-full h-auto object-cover">
                                        <!-- Nouveau conteneur pour centrer l'icône -->
                                        <div class="play-icon-container">
                                            <div class="play-icon"></div>
                                        </div>
                                    @endif
                                    <div class="p-4">
                                        <h3 class="text-lg font-semibold text-gray-800">{{ $video->title }}</h3>
                                        <p class="text-gray-600 mt-2">
                                            {{ \Illuminate\Support\Str::limit(strip_tags($video->description), 100) }}
                                        </p>
                                        <p class="text-sm text-gray-500 mt-2">{{ $video->created_at->format('d/m/Y') }}</p>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </section>

        <div class="albums-link-container">
            <x-button 
                route="{{ route('videos.index') }}"
                buttonText="{{ __('messages.our_videos') }}" 
                primaryColor="#B91C1C" 
                secondaryColor="#DC2626" 
            />
        </div>
    </div>

    <!-- Image Modal -->
    <div id="imageModal" class="modal">
        <span class="close" onclick="closeImageModal()">&times;</span>
        <div class="modal-content-wrapper">
            <img class="modal-content" id="modalImage">
            <div id="caption" class="image-caption"></div>
        </div>
        <a class="prev" onclick="changeSlide(-1)">&#10094;</a>
        <a class="next" onclick="changeSlide(1)">&#10095;</a>
    </div>
</section>

<script>
  // Effet de texte animé pour le message de bienvenue
document.addEventListener("DOMContentLoaded", function() {
    const typingElement = document.getElementById("typing-text");
    const text = {!! json_encode($flashMessage->homemessage ?? "") !!};  // Utilisez json_encode pour traiter le texte
    let index = 0;
    const speed = window.innerWidth <= 768 ? 25 : 50;  // Vitesse d'écriture plus rapide sur mobile

    // Fonction pour afficher le logo et le bouton après le typing effect
    function showLogoAndButton() {
        const logo = document.getElementById("club-logo");
        const button = document.getElementById("reserve-button");

        logo.style.display = "block";
        button.style.display = "inline-block";

        // Ajuster les positions pour mobile ou non
        if (window.innerWidth <= 768) {
            logo.style.marginTop = "5vh"; // Positionnement plus haut sur mobile
            button.style.marginTop = "30vh"; // Positionnement plus haut sur mobile
        } else {
            logo.style.marginTop = "30vh"; // Positionnement par défaut sur desktop
            button.style.marginTop = "60vh"; // Positionnement par défaut sur desktop
        }

        setTimeout(() => {
            logo.style.opacity = "1";
        }, 100);

        setTimeout(() => {
            button.style.opacity = "1";
        }, 500);
    }

    // Fonction pour formater le texte avec un saut de ligne après les 15 premiers caractères
    function formatTextWithFirstLineBreak(text) {
        if (text.length > 16) {
            return text.slice(0, 16) + '\n' + text.slice(15);
        }
        return text;
    }

    // Fonction pour l'effet d'écriture du texte
    function typeWriter(formattedText) {
        if (index < formattedText.length) {
            const char = formattedText.charAt(index);
            if (char === '\n') {
                typingElement.innerHTML += '<br>';
            } else {
                typingElement.innerHTML += char;
            }
            index++;
            setTimeout(() => typeWriter(formattedText), speed);
        } else {
            showLogoAndButton();
        }
    }

    typingElement.innerHTML = "";  // Efface le contenu avant de commencer l'effet de texte
    const formattedText = formatTextWithFirstLineBreak(text);

    if (formattedText && formattedText.length > 0) {
        typeWriter(formattedText);
    } else {
        showLogoAndButton();
    }
});

// Gestion des images de la galerie (Latest Photos)
let slideIndex = 0;
let photos = @json($latestPhotos);  // Les photos de la galerie

function openImageModal(index) {
    slideIndex = index;
    showSlide(slideIndex);
    document.getElementById("imageModal").style.display = "flex";
    document.body.style.overflow = "hidden"; // Désactive le scroll de la page
    document.getElementById("imageModal").classList.add('modal-background-dark'); // Ajoute l'arrière-plan sombre
}

function closeImageModal() {
    document.getElementById("imageModal").style.display = "none";
    document.body.style.overflow = "auto"; // Réactive le scroll de la page
    document.getElementById("imageModal").classList.remove('modal-background-dark'); // Retire l'arrière-plan sombre
}

function changeSlide(n) {
    slideIndex += n;
    if (slideIndex >= photos.length) slideIndex = 0;
    if (slideIndex < 0) slideIndex = photos.length - 1;
    showSlide(slideIndex);
}

function showSlide(index) {
    let photo = photos[index];
    document.getElementById("modalImage").src = `/storage/${photo.image}`;
    document.getElementById("caption").innerText = photo.caption ? photo.caption : '';
}

// Gestion du carousel des articles (Latest Articles)
let currentIndex = 0;
const slides = document.querySelectorAll('.carousel .main-article-container');
const dots = document.querySelectorAll('.dot span');
const intervalTime = 5000; // 5 secondes entre chaque changement automatique de slide

function goToSlide(index) {
    if (index < 0) {
        index = slides.length - 1;
    } else if (index >= slides.length) {
        index = 0;
    }

    slides.forEach(slide => slide.classList.remove('active'));
    slides[index].classList.add('active');
    document.querySelector('.carousel').style.transform = `translateX(-${index * 100}%)`;

    dots.forEach((dot, i) => {
        dot.textContent = i === index ? '⚫️' : '⚪';
    });

    currentIndex = index;
}

let autoSlideInterval;

function startAutoSlide() {
    autoSlideInterval = setInterval(() => {
        currentIndex++;
        goToSlide(currentIndex);
    }, intervalTime);
}

function stopAutoSlide() {
    clearInterval(autoSlideInterval);
}

document.querySelectorAll('.main-article-container').forEach(container => {
    container.addEventListener('mouseenter', stopAutoSlide); // Arrête le défilement au survol
    container.addEventListener('mouseleave', startAutoSlide); // Redémarre le défilement quand la souris quitte
});

document.addEventListener('DOMContentLoaded', function () {
    goToSlide(currentIndex); // Affiche la première slide
    startAutoSlide(); // Démarre le défilement automatique des articles
});
// Initialisation


</script>

<!-- Footer -->
<x-footerforhome />

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>