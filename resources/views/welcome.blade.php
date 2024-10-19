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
                height: auto; 
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

            .containerization {
            width: 100%;
            max-width: 900px;
            margin: auto;
            border: 4px solid {{ $primaryColor }};
            border-radius: 15px;
            margin-bottom: 100px;
            margin-top: 30px;
        }

        .match-card {
            border-radius: 15px;
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

        /* Ajouter ces propriétés pour gérer l'image dans la modal */
        .modal-content-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            height: 100%;
        }

        .modal-content {
            width: auto; /* Permet à l'image de s'adapter à sa taille d'origine */
            height: auto; /* Permet à l'image de s'adapter à sa taille d'origine */
            max-width: 90%; /* Limite la largeur maximale à 90% de la modal */
            max-height: 90%; /* Limite la hauteur maximale à 90% de la modal */
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
                width: 90%; /* Rend le conteneur plus large sur mobile */
                top: 80%; /* Ajuste la position pour mobile */
            }

            .match-info {
                display: flex;
                flex-direction: column;
                align-items: center;
            }

            .match-details {
                flex-direction: row;
                justify-content: center;
                align-items: center;
            }

            .match-info span {
                display: none; /* Cache les noms des clubs */
            }

            .team-logo img {
                width: 90px; /* Ajuste la taille des logos pour mobile */
                height: 90px;
            }

            .vs-text {
                font-size: 1.5rem; /* Réduit la taille du texte "VS" */
                margin: 0 10px; /* Ajuste l'espacement */
            }

            .match-location, .match-date {
                font-size: 1rem; /* Ajuste la taille du texte */
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
                max-width: 100%;
                width: 100%; /* Ensure width is capped at 100% */
                overflow-x: hidden; /* Hide overflow */
            }

            .containerization {
                max-width: 100%; /* Ensure it doesn't exceed the screen width */
                width: 100%;
            }
        }

        @media (max-width: 768px) {
            .carousel-container {
                width: 100% !important;
                min-height: 20vh !important;
                overflow: hidden !important;
            }

            .main-article-container {
                position: relative !important;
                width: 100% !important;
                height: auto !important;
            }

            .main-article-image img {
                height: auto; /* Adapter la hauteur de l'image au conteneur */
                max-height: 500px; 
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
    </style>
</head>
<body class="bg-gray-100">
    <!-- Navbar -->
    <x-navbar />

    <div class="flash-message-container">
        <div class="weather-info">
            <li class="d-flex align-items-center justify-content-center">
                <img src="{{ asset('weather.png') }}" alt="Position" class="h-6 w-6 mr-2 ml-2"> 
                <div class="mr-2">{{ $city }} | <span style="font-weight: bold;">{{ $weatherData['main']['temp'] ?? 'N/A' }}°C</span></div>
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
        <div id="typing-text" style="position: absolute;  left: 50%; transform: translateX(-50%); color: {{ $secondaryColor }}; font-family: 'Bebas Neue', sans-serif; font-size: 6rem; font-weight: bold; text-align: center; text-shadow: 2px 2px 5px rgba(0,0,0,0.7); z-index: 1300;">
{{ $flashMessage->homemessage ?? '' }}
        </div>

        <img id="club-logo" src="{{ $logoPath ? asset($logoPath) : '' }}" alt="Club Logo" style="display:none; width: 150px; position: absolute; left: 50%; transform: translateX(-50%); margin-top: 30vh; opacity: 0; transition: opacity 1s ease-in-out;">

        <a id="reserve-button" href="{{ route('fanshop.index') }}" style="display:none; padding: 15px 30px; color: white; font-family: 'Bebas Neue', sans-serif; font-size: 1.5rem; border: none; border-radius: 5px; cursor: pointer; text-decoration: none; position: absolute; left: 50%; transform: translateX(-50%); margin-top: 50vh; opacity: 0; transition: opacity 1s ease-in-out;" onmouseover="this.style.backgroundColor='{{ $primaryColor }}';"
        onmouseout="this.style.backgroundColor='{{ $secondaryColor }}';">
            @lang('messages.reserve_your_ticket')
        </a>
    </div>

<div class="info-container" style="top:105vh; z-index: 1300;">
    <div class="title">@lang('messages.next_game')</div>
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
                    <div class="match-location">@lang('messages.away')</div>
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
            <p style="text-align: center; font-size: 16px; font-weight: bold;">@lang('messages.no_upcoming_games')</p>
        </div>
    @endif
</div>

    <hr style="margin-bottom:50px;">

    <section class="news-section">
    <div style="text-align:center;">
        <x-page-title subtitle="🔔 {{ __('messages.stay_informed') }}">
            @lang('messages.latest_news')
        </x-page-title>
    </div>

    @if($articles->isEmpty())
        <p class="text-center text-gray-600">No news has been added yet.</p>
    @else
        <div class="carousel-container relative w-full min-h-screen flex justify-center items-center z-50" data-aos="fade-right">
            <div class="carousel flex transition-transform duration-500 ease-in-out w-[80%]">
                @foreach($articles as $index => $article)
                    <div class="main-article-container flex flex-col lg:flex-row flex-none w-full relative">
                        <!-- Main Article Image -->
                        <div class="main-article-image w-full h-full max-h-[500px]">
                            <a href="{{ route('articles.show', $article->slug) }}">
                                @if($article->image)
                                    <img class="w-full h-full object-cover" src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->title }}">
                                @endif
                            </a>
                        </div>

                        <!-- Main Article Content for Desktop and Tablet (Black Opacity Background) -->
                        <div class="hidden lg:flex justify-center items-center absolute inset-0">
                            <div class="bg-black bg-opacity-50 text-white w-1/2 p-8 rounded-lg flex flex-col justify-center items-center">
                                <p class="bg-{{ $primaryColor }} text-white text-xs mb-4 uppercase font-bold px-2 py-1 rounded">
                                    @lang('messages.news')
                                </p>
                                <h2 class="text-3xl font-bold mb-2 article-title text-center">
                                    <strong>{{ $article->title }}</strong>
                                </h2>
                                <p class="text-sm mb-4 text-center">
                                    @lang('messages.published_on'): {{ $article->created_at->format('d M Y, H:i') }} @lang('messages.by') {{ $article->user->name }}
                                </p>
                                <!-- Buttons Container -->
                                <div class="flex mt-4 gap-4">
                                    <x-button 
                                        route="{{ route('articles.show', $article->slug) }}"
                                        buttonText="{{ __('messages.read_more') }}" 
                                        primaryColor="#B91C1C" 
                                        secondaryColor="#DC2626" 
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- Main Article Content for Mobile (Content Under Image) -->
                        <div class="lg:hidden w-full flex flex-col items-center justify-center bg-gray-100 p-4 mx-auto">
                            <p class="bg-{{ $primaryColor }} text-white text-xs mb-2 uppercase font-bold px-2 py-1 rounded w-max mx-auto">
                                @lang('messages.news')
                            </p>
                            <h2 class="text-xl font-bold mb-2 text-center mx-auto">
                                <strong>{{ $article->title }}</strong>
                            </h2>
                            <p class="text-sm mb-2 text-center mx-auto">
                                @lang('messages.published_on'): {{ $article->created_at->format('d M Y, H:i') }} @lang('messages.by') {{ $article->user->name }}
                            </p>
                            <div class="flex justify-center mt-2 mx-auto">
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
            <div class="navigation-dots absolute bottom-20 flex justify-center w-full mt-10">
                @foreach($articles as $index => $article)
                    <div id="dot-{{ $index }}" class="dot cursor-pointer mx-1" onclick="goToSlide({{ $index }})">
                        <span id="emoji-{{ $index }}">{{ $index === 0 ? '⚫️' : '⚪' }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</section>

<!-- Media Queries -->
<style>
    /* For screens with a width of 1020px or less */
    @media screen and (max-width: 1020px) {
        .carousel-container {
            margin-bottom: 50px;
        }
    }

    @media screen and (max-width: 768px) {
        .navigation-dots {
            bottom: -20px; /* Décalage vers le bas de 20px */
        }
    }

    /* For screens with a width of 769px or less */

</style>

    <hr class ="mt-10" style="margin-bottom:50px;">

<!-- Section pour les 5 prochains matchs -->
<div style="text-align:center">
    <x-page-title style="text-align:center" subtitle="{{ __('messages.get_ready') }}">
        @lang('messages.upcoming_matches')
    </x-page-title>
</div>

@if($nextGames->isNotEmpty())
    <div class="containerization" data-aos="fade-right">
        @foreach($nextGames as $index => $game)
            <div class="match-card {{ $index % 2 == 0 ? 'even' : 'odd' }}">
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
                <div class="match-details">
                    <div class="team-logo" data-aos="fade-right">
                        <img src="{{ asset('storage/' . $game->homeTeam->logo_path) }}" alt="{{ $game->homeTeam->name }} Logo">
                    </div>
                    <div class="match-info">
                        <p>{{ $game->homeTeam->name }} <strong>vs</strong> {{ $game->awayTeam->name }}</p>
                    </div>
                    <div class="team-logo" data-aos="fade-left">
                        <img src="{{ asset('storage/' . $game->awayTeam->logo_path) }}" alt="{{ $game->awayTeam->name }} Logo">
                    </div>
                </div>
            </div>
        @endforeach
        <div class="see-all-container">
            <x-button 
                route="{{ route('calendar.show', ['team_filter' => 'specific_team', 'date_filter' => 'upcoming']) }}#calendar-section"
                buttonText="{{ __('messages.see_all_matches') }}" 
                primaryColor="#B91C1C" 
                secondaryColor="#DC2626" 
            />
        </div>
    </div>
@else
    <div style="text-align:center; margin-top: 20px;">
        <p class="text-center text-gray-600">@lang('messages.no_upcoming_games')</p>
    </div>
@endif


    <hr class ="mt-10" style="margin-bottom:50px;">

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


        <hr class="mt-10" style="margin-bottom:50px;">

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
      document.addEventListener("DOMContentLoaded", function() {
            const typingElement = document.getElementById("typing-text");
            const text = {!! json_encode($flashMessage->homemessage ?? "") !!};  // Utilisez json_encode pour traiter le texte
            let index = 0;
            const speed = window.innerWidth <= 768 ? 25 : 50;  // Vitesse d'écriture plus rapide sur mobile

            // Fonction pour afficher le logo et le bouton
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
                    button.style.marginTop = "50vh"; // Positionnement par défaut sur desktop
                }

                setTimeout(() => {
                    logo.style.opacity = "1";
                }, 100);

                setTimeout(() => {
                    button.style.opacity = "1";
                }, 500);
            }

            // Fonction pour formater le texte en insérant un saut de ligne après les 15 premiers caractères
            function formatTextWithFirstLineBreak(text) {
                if (text.length > 16) {
                    // Insère un saut de ligne après les 15 premiers caractères
                    return text.slice(0, 16) + '\n' + text.slice(15);
                }
                return text;  // Si le texte est plus court que 15 caractères, ne rien changer
            }

            // Fonction pour le typing effect
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

            // Efface le contenu de l'élément avant de commencer le typing effect
            typingElement.innerHTML = "";

            // Formater le texte pour inclure un saut de ligne après les 15 premiers caractères
            const formattedText = formatTextWithFirstLineBreak(text);

            // Lancer le typing effect si du texte est présent, sinon afficher immédiatement le logo et le bouton
            if (formattedText && formattedText.length > 0) {
                typeWriter(formattedText);
            } else {
                showLogoAndButton();
            }
        });

    let slideIndex = 0;
    let photos = @json($latestPhotos);

    function openImageModal(index) {
        slideIndex = index;
        showSlide(slideIndex);
        document.getElementById("imageModal").style.display = "flex";
    }

    function closeImageModal() {
        document.getElementById("imageModal").style.display = "none";
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

    let currentIndex = 0;
    const slides = document.querySelectorAll('.carousel .main-article-container');
    const dots = document.querySelectorAll('.dot span');
    const intervalTime = 5000;

    function goToSlide(index) {
        // Assurez-vous que l'index est dans les limites
        if (index < 0) {
            index = slides.length - 1; // Aller au dernier article si on dépasse à gauche
        } else if (index >= slides.length) {
            index = 0; // Retourner au premier article si on dépasse à droite
        }

        // Supprimer la classe active de tous les slides
        slides.forEach(slide => slide.classList.remove('active'));

        // Ajouter la classe active au slide actuel
        slides[index].classList.add('active');

        // Déplacer le carousel
        document.querySelector('.carousel').style.transform = `translateX(-${index * 100}%)`;

        // Mise à jour des dots
        dots.forEach((dot, i) => {
            dot.textContent = i === index ? '⚫️' : '⚪'; // Mettre à jour le dot actif
        });

        // Mettre à jour l'index actuel
        currentIndex = index;
    }

    function startAutoSlide() {
        setInterval(() => {
            currentIndex++;
            goToSlide(currentIndex);
        }, intervalTime);
    }

    // Attacher les événements de clic aux dots
    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            goToSlide(index); // Associer chaque dot à son slide correspondant
        });
    });

    // Initialisation
    document.addEventListener('DOMContentLoaded', function () {
        goToSlide(currentIndex); // Afficher la première slide
        startAutoSlide(); // Démarrer le défilement automatique
    });
</script>

<!-- Footer -->
<x-footerforhome />

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
