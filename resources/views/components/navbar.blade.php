@php
    $primaryColor = $primaryColor ?? '#1F2937'; // Gris par défaut
    $secondaryColor = $secondaryColor ?? '#FF0000'; // Rouge par défaut
    $logoPath = $logoPath ?? null;
    $clubName = $clubName ?? 'Default Club Name';
    $clubLocation = $clubInfo->sportcomplex_location ?? 'Default Location'; // Ajout de la location par défaut
@endphp


@php
    use Illuminate\Support\Facades\Route;
@endphp

@php
    if (isset($_COOKIE['locale'])) {
        app()->setLocale($_COOKIE['locale']);
    }
@endphp


<style>
    @import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap');

    .custom-font {
        font-family: 'Bebas Neue', sans-serif;
    }

    .nav-link:hover {
        color: {{ $secondaryColor }} !important;
    }

    .nav-link-container {
    position: relative;
    display: inline-block;
}

.nav-link-hr {
    position: absolute;
    bottom: -2px; /* Ajuster la position du hr sous le lien */
    left: 0;
    width: 0;
    height: 2px; /* Vous pouvez ajuster cette hauteur */
    background-color: {{ $secondaryColor }};
    transition: width 0.3s ease-in-out;
}

.nav-link-container:hover .nav-link-hr {
    width: 100%;
}

/* Définir le dropdown */
.dropdown {
    position: relative;
    display: inline-block;
    z-index: 600;
}

/* Ajuster la visibilité et l'animation du contenu du dropdown */
.dropdown-content {
    visibility: hidden;
    position: absolute;
    background-color: {{ $primaryColor }};
    z-index: 1100;
    width: 200px;
    box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
    text-align: left;
    opacity: 0;
    transform: translateY(-10px);
    transition: opacity 0.5s ease, transform 0.5s ease;
    border-radius: 0 0 8px 8px;
    margin-top: 1px;
    border: 1px solid {{ $secondaryColor }};
    pointer-events: none; /* Empêche le menu de bloquer la détection du hover en dehors */
}

/* Ajuster le comportement du lien dans le dropdown */
.dropdown-content a {
    color: white;
    padding: 10px 14px;
    text-decoration: none;
    display: block;
    transition: ease-in-out 0.5s, color 0.3s ease;
    font-size: 0.875rem;
}

/* Changement de couleur lors du survol d'un lien */
.dropdown-content a:hover {
    background-color: {{ $secondaryColor }};
    color: white;
    border-radius: 0 0 8px 8px;
}

/* Montrer le contenu avec un léger délai au survol */
.dropdown:hover .dropdown-content {
    visibility: visible;
    opacity: 1;
    transform: translateY(0);
    transition-delay: 0.3s; /* Délai avant d'afficher le dropdown */
    pointer-events: auto; /* Permet l'interaction avec le menu */
}

/* Masquer doucement le contenu lorsque la souris quitte */
.dropdown:not(:hover) .dropdown-content {
    visibility: visible;  /* Laisse visible pour la transition */
    opacity: 0;
    transform: translateY(-10px);
    transition: opacity 0.5s ease, transform 0.5s ease;
    pointer-events: none;  /* Bloque les événements lorsqu'il est caché */
}

/* Flèche au-dessus du menu déroulant */
.dropdown-content::before {
    content: '';
    position: absolute;
    top: -10px;
    left: 50%;
    transform: translateX(-50%);
    width: 0;
    height: 0;
    border-top: 10px solid transparent;
    border-left: 10px solid transparent;
    border-right: 10px solid transparent;
    border-bottom: 10px solid {{ $primaryColor }};
    z-index: -1;
}

/* Style de séparation entre les liens du dropdown */
.dropdown-content a:not(:last-child) {
    border-bottom: 1px solid rgba(255, 255, 255, 0.2);
}

.no-scroll {
    overflow: hidden;
}

#mobile-menu-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw; 
    height: 100vh;
    background-color: rgba(0, 0, 0, 0.8);
    transform: translateX(-100%); /* Position initiale en dehors de l'écran à gauche */
    transition: transform 0.3s ease-in-out;
    z-index: 1400;
    overflow: hidden;
    font-family: 'Bebas Neue', sans-serif;
    font-size: 25px;
}

#mobile-menu-modal .w-64 {
    background-color: {{ $primaryColor }} !important;
    color: white !important;
    font-family: 'Bebas Neue', sans-serif;
    z-index: 1050;
    max-height: 150vh; /* Limite la hauteur à la taille de l'écran */
    overflow-y: auto; /* Ajoute le défilement vertical */
    padding-bottom: 20px;
}

#mobile-menu-modal .w-64 a {
    color: white !important;
}

#mobile-menu-modal .w-64 a:hover {
    color: {{ $secondaryColor }} !important;
}

#mobile-menu-modal.open {
    transform: translateX(0);
}

#hamburger-button {
    font-size: 30px;
    background: none;
    border: none;
    cursor: pointer;
}

#close-button {
    font-size: 30px;
    background: none;
    border: none;
    cursor: pointer;
}

@media (max-width: 1336px) {
    .desktop-nav {
        display: none;
    }
    .mobile-nav {
        display: block;
    }
}

@media (min-width: 1337px) {
    .desktop-nav {
        display: flex;
    }
    .mobile-nav {
        display: none;
    }
}

@media (max-width: 768px) {
    
    .clubName {
        display: none; /* Masque l'image de bienvenue */
    }

     #reserve-button {
        padding: 10px 20px; /* Réduction du padding pour un bouton plus petit */
        font-size: 1rem; /* Réduction de la taille de la police */
        white-space: nowrap; /* Empêche le texte de se diviser en plusieurs lignes */
    }
}
</style>


<nav class="p-10" style="background-color: {{ $primaryColor }};">
    <div class="container mx-auto flex justify-between items-center custom-font" style="font-size: 25px; letter-spacing: 2px;">
<!-- Logo -->
<div class="flex items-center">
    <img src="{{ $logoPath ?? asset('unknown.png') }}" alt="@lang('messages.logo_not_available')" style="height: 80px; width: auto;">
    <div class="clubName ml-3 text-white px-2 py-1 border-4" style="border-color: {{ $secondaryColor }};">
        {{ $clubName }}
    </div>
</div>

       <!-- Navigation links (hidden on mobile, shown on desktop) -->
<div class="desktop-nav space-x-8">
    <div class="nav-link-container">
        <a href="/" class="text-white nav-link transition duration-200">@lang('messages.home')</a>
        <div class="nav-link-hr"></div>
    </div>

    <div class="nav-link-container dropdown">
        <a href="#" class="text-white nav-link transition duration-200" style="display: flex; align-items: center;">
            @lang('messages.club') <img src="{{ asset('bas.png') }}" alt="▼" style="width: 24px; height: 24px; margin-left: 5px;">
        </a>
        <div class="nav-link-hr"></div>
        <div class="dropdown-content">
            <a href="{{ route('about.index') }}">@lang('messages.about')</a>
            <a href="{{ route('clubinfo') }}">@lang('messages.news')</a>
            <a href="{{ route('press_releases.index') }}">@lang('messages.press_releases')</a>
            <a href="{{ route('galleries.index') }}">@lang('messages.gallery')</a>
            <a href="{{ route('videos.index') }}">@lang('messages.videos')</a>
        </div>
    </div>

    <!-- Sous-menu ajouté sous Competition -->
    <div class="nav-link-container dropdown">
        <a href="#" class="text-white nav-link transition duration-200" style="display: flex; align-items: center;">
            @lang('messages.competition') <img src="{{ asset('bas.png') }}" alt="▼" style="width: 24px; height: 24px; margin-left: 5px;">
        </a>
        <div class="nav-link-hr"></div>
        <div class="dropdown-content">
        <a href="{{ route('calendar.show') }}#ranking-section">@lang('messages.ranking')</a>
        <a href="{{ route('calendar.show') }}#calendar-section">@lang('messages.calendar')</a>
        </div>
    </div>

    <div class="dropdown nav-link-container">
        <a href="{{ route('teams') }}" class="text-white nav-link transition duration-200" style="display: flex; align-items: center;">
            @lang('messages.teams') <img src="{{ asset('bas.png') }}" alt="▼" style="width: 24px; height: 24px; margin-left: 5px;">
        </a>
        <div class="nav-link-hr"></div>
        <div class="dropdown-content">
            <a href="{{ route('teams') }}">@lang('messages.senior')</a>
            <a href="{{ route('playersu21.index') }}">@lang('messages.u21')</a>
        </div>
    </div>

    <div class="nav-link-container">
        <a href="{{ route('sponsors.index') }}" class="text-white nav-link transition duration-200">@lang('messages.sponsors')</a>
        <div class="nav-link-hr"></div>
    </div>

    <div class="nav-link-container">
        <a href="{{ route('contact.show') }}" class="text-white nav-link transition duration-200">@lang('messages.contact')</a>
        <div class="nav-link-hr"></div>
    </div>

    <div class="nav-link-container">
        <a href="{{ route('fanshop.index') }}" class="text-white nav-link transition duration-200">@lang('messages.fanshop')</a>
        <div class="nav-link-hr"></div>
    </div>

    @auth
    <div class="nav-link-container">
        <a href="{{ url('/dashboard') }}" class="text-white nav-link transition duration-200 px-4 border-2 rounded-full" style="border-color: {{ $secondaryColor }};">
            @lang('messages.dashboard')
        </a>
    </div>
    @endauth


            <ul class="language-switcher">
    <li class="language-item {{ app()->getLocale() == 'en' ? 'active' : '' }}">
        <a href="#" onclick="setLanguage('en')">EN</a>
    </li>
    <li class="language-item {{ app()->getLocale() == 'ar' ? 'active' : '' }}">
        <a href="#" onclick="setLanguage('ar')">AR</a>
    </li>
</ul>

        <style>
      .language-switcher {
    display: flex;
    list-style-type: none;
    padding: 0;
    margin: 0;
    color:white;
}

.language-item {
    margin-right: 15px;
    display: flex;
    align-items: center;
    transition: color 0.3s, border-bottom 0.3s;
    border-bottom: 2px solid transparent;
}

.language-item.active {
    color: {{ $secondaryColor }}; /* Couleur pour la langue active */
    border-bottom: 2px solid {{ $secondaryColor }}; /* Indicateur pour la langue active */
}

.language-item a {
    text-decoration: none;
    color: inherit;
    font-weight: bold;
    transition: color 0.3s;
}

.language-item a:hover {
    color: {{ $secondaryColor }};
}

.language-item.active img {
    filter: brightness(0.8); /* Sombre légèrement l'icône de la langue active */
}
</style>
        <script>
            function setLanguage(locale) {
                document.cookie = "locale=" + locale;
                location.reload();
            }
        </script>
        </div>

        <!-- Hamburger menu (shown on mobile, hidden on desktop) -->
        <div class="mobile-nav">
            <button id="hamburger-button" class="text-white">
                &#9776;
            </button>
        </div>
    </div>
</nav>

<div id="mobile-menu-modal" class="fixed inset-0 z-50 bg-gray-800 bg-opacity-75 flex items-start">
    <div class="w-64 h-full bg-white p-4">
        <!-- Logo et Nom du Club -->
        <div class="flex items-center mb-6">
            @if($logoPath)
                <img src="{{ $logoPath }}" alt="Club Logo" style="height: 50px; width: auto;">
            @endif
            <div class="ml-3 text-white px-2 py-1 border-4" style="border-color: {{ $secondaryColor }}; font-size: 20px;">
                {{ $clubName }}
            </div>
        </div>

        <!-- Sélection de la langue -->
        <div class="py-2 flex justify-center">
            <ul class="language-switcher flex" style="list-style-type: none; padding: 0; margin: 0;">
                <li class="language-item {{ app()->getLocale() == 'en' ? 'active' : '' }}" style="margin-right: 15px;">
                    <a href="#" onclick="setLanguage('en')" style="text-decoration: none; color: inherit; font-weight: bold;">EN</a>
                </li>
                <li class="language-item {{ app()->getLocale() == 'ar' ? 'active' : '' }}">
                    <a href="#" onclick="setLanguage('ar')" style="text-decoration: none; color: inherit; font-weight: bold;">AR</a>
                </li>
            </ul>
        </div>

        <!-- Bouton de fermeture -->
        <button id="close-button" class="text-white">&times;</button>

        <!-- Menu -->
        <a href="/" class="block text-white py-2">@lang('messages.home')</a>

        <!-- Club Dropdown -->
        <a href="#" class="block text-white py-2" onclick="toggleDropdown('clubDropdown')" style="display: flex; align-items: center;">
            @lang('messages.club') <img src="{{ asset('bas.png') }}"  alt="▼" style="width: 24px; height: 24px; margin-left: 5px;">
        </a>

        <div id="clubDropdown" class=" pl-4"  style="max-height: 0px; overflow: hidden; transition: max-height 0.5s ease;">
            <a href="{{ route('about.index') }}" class="block text-white py-2">@lang('messages.about')</a>
            <a href="{{ route('clubinfo') }}" class="block text-white py-2">@lang('messages.news')</a>
            <a href="{{ route('press_releases.index') }}" class="block text-white py-2">@lang('messages.press_releases')</a>
            <a href="{{ route('galleries.index') }}" class="block text-white py-2">@lang('messages.gallery')</a>
            <a href="{{ route('videos.index') }}" class="block text-white py-2">@lang('messages.videos')</a>
        </div>

        <!-- Competition Dropdown -->
        <a href="#" class="block text-white py-2" onclick="toggleDropdown('competitionDropdown')" style="display: flex; align-items: center;">
            @lang('messages.competition') <img src="{{ asset('bas.png') }}" alt="▼" style="width: 24px; height: 24px; margin-left: 5px;">
        </a>

        <div id="competitionDropdown" class=" pl-4"  style="max-height: 0px; overflow: hidden; transition: max-height 0.5s ease;">
            <a href="{{ route('calendar.show') }}#ranking-section" class="block text-white py-2">@lang('messages.ranking')</a>
            <a href="{{ route('calendar.show') }}#calendar-section" class="block text-white py-2">@lang('messages.calendar')</a>
        </div>

        <!-- Teams Dropdown -->
        <a href="#" class="block text-white py-2" onclick="toggleDropdown('teamsDropdown')" style="display: flex; align-items: center;">
            @lang('messages.teams') <img src="{{ asset('bas.png') }}"  alt="▼" style="width: 24px; height: 24px; margin-left: 5px;">
        </a>
        <div id="teamsDropdown" class=" pl-4"  style="max-height: 0px; overflow: hidden; transition: max-height 0.5s ease;">
            <a href="{{ route('teams') }}" class="block text-white py-2">@lang('messages.senior')</a>
            <a href="{{ route('playersu21.index') }}" class="block text-white py-2">@lang('messages.u21')</a>
        </div>

        <a href="{{ route('sponsors.index') }}" class="block text-white py-2">@lang('messages.sponsors')</a>
        <a href="{{ route('contact.show') }}" class="block text-white py-2">@lang('messages.contact')</a>
        <a href="{{ route('fanshop.index') }}" class="block text-white py-2">@lang('messages.fanshop')</a>
        @auth
        <a href="{{ url('/dashboard') }}" class="block text-white py-2">@lang('messages.dashboard')</a>
        @endauth
    </div>
</div>

<div id="scroll-to-top" style="display: none; position: fixed; bottom: 20px; right: 20px; z-index: 2000; cursor: pointer;">
    <img src="{{ asset('haut.png') }}"  alt="" width="35px">
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const scrollToTopButton = document.getElementById('scroll-to-top');

        // Afficher le bouton lorsque l'utilisateur défile vers le bas
        window.addEventListener('scroll', function () {
            if (window.scrollY > 200) {
                scrollToTopButton.style.display = 'block';
            } else {
                scrollToTopButton.style.display = 'none';
            }
        });

        // Remonter en haut de la page lorsque l'utilisateur clique sur le bouton
        scrollToTopButton.addEventListener('click', function () {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    });

    // Attendre que le document soit prêt
    document.addEventListener('DOMContentLoaded', function () {
        // Sélectionner l'élément d'alerte
        var successAlert = document.getElementById('success-alert');
        
        // Vérifier si l'alerte existe
        if (successAlert) {
            // Faire disparaître l'alerte après 3 secondes
            setTimeout(function () {
                successAlert.style.transition = 'opacity 0.5s ease';
                successAlert.style.opacity = '0';
                
                // Supprimer complètement l'élément après l'animation
                setTimeout(function() {
                    successAlert.remove();
                }, 500);
            }, 3000);
        }
    });

    document.addEventListener('DOMContentLoaded', function () {
    const hamburgerButton = document.getElementById('hamburger-button');
    const mobileMenuModal = document.getElementById('mobile-menu-modal');
    const closeButton = document.getElementById('close-button');

    hamburgerButton.addEventListener('click', function () {
        mobileMenuModal.classList.add('open');  // Affiche la modal
        document.body.classList.add('no-scroll');  // Désactive le défilement de l'arrière-plan
    });

    closeButton.addEventListener('click', function () {
        mobileMenuModal.classList.remove('open');  // Cache la modal
        document.body.classList.remove('no-scroll');  // Réactive le défilement de l'arrière-plan
    });

    // Ferme la modal si on clique en dehors
    mobileMenuModal.addEventListener('click', function(e) {
        if (e.target.id === 'mobile-menu-modal') {
            mobileMenuModal.classList.remove('open');
            document.body.classList.remove('no-scroll');
        }
    });
});

    function toggleDropdown(dropdownId) {
    var dropdown = document.getElementById(dropdownId);

    if (dropdown.style.maxHeight === '0px' || dropdown.style.maxHeight === '') {
        // On ouvre en ajustant la hauteur à la hauteur du contenu
        dropdown.style.maxHeight = dropdown.scrollHeight + 'px';
    } else {
        // On referme en mettant la hauteur à 0
        dropdown.style.maxHeight = '0px';
    }
}

    function closeMobileMenu() {
        document.getElementById('mobile-menu-modal').classList.remove('open');
    }
    
</script>