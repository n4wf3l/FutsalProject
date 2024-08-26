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

.dropdown {
    position: relative;
    display: inline-block;
    transition: transform 0.3s ease-in-out;
    z-index: 600;
}

.dropdown-content {
    display: none;
    position: absolute;
    background-color: {{ $primaryColor }};
    z-index: 1100; 
    width: 200px;
    box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
    text-align: left;
    opacity: 0;
    transform: translateY(0); 
    transition: opacity 0.3s ease, transform 0.3s ease;
    border-radius: 0 0 8px 8px;
    overflow: hidden;
    margin-top: 3px;
    border: 1px solid {{ $secondaryColor }};
}

.dropdown-content a {
    color: white;
    padding: 10px 14px;
    text-decoration: none;
    display: block;
    transition: background-color 0.3s ease, color 0.3s ease;
    font-size: 0.875rem;
}

.dropdown-content a:hover {
    background-color: {{ $secondaryColor }};
    color: white;
}

.dropdown:hover .dropdown-content {
    display: block;
    opacity: 1;
    transform: translateY(0);
}

.dropdown:hover .dropdown-content {
    display: block;
    opacity: 1;
    transform: translateY(0);
}

.dropdown-content::before {
    content: '';
    position: absolute;
    top: -10px;
    left: 50%;
    transform: translateX(-50%);
    width: 0;
    height: 0;
    border-left: 10px solid transparent;
    border-right: 10px solid transparent;
    border-bottom: 10px solid {{ $primaryColor }};
    z-index: -1;
}

.dropdown-content a:not(:last-child) {
    border-bottom: 1px solid rgba(255, 255, 255, 0.2);
}

    #mobile-menu-modal {
    transition: transform 0.3s ease-in-out;
    transform: translateX(-100%);
    font-family: 'Bebas Neue', sans-serif;
    font-size:25px;
    z-index: 1400;
}

#mobile-menu-modal .w-64 {
    background-color: {{ $primaryColor }} !important;
    color: white !important;
    font-family: 'Bebas Neue', sans-serif;
    z-index: 1050;
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
}
</style>


<nav class="p-10" style="background-color: {{ $primaryColor }};">
    <div class="container mx-auto flex justify-between items-center custom-font" style="font-size: 25px; letter-spacing: 2px;">
        <!-- Logo -->
        <div class="flex items-center">
            @if($logoPath)
                <img src="{{ $logoPath }}" alt="Site Logo" style="height: 80px; width: auto;">
            @else
                <p>Logo non disponible</p>
            @endif
            <div class="clubName ml-3 text-white px-2 py-1 border-4" style="border-color: {{ $secondaryColor }};">
                {{ $clubName }}
            </div>
        </div>

        <!-- Navigation links (hidden on mobile, shown on desktop) -->
        <div class="desktop-nav space-x-8">
    <div class="nav-link-container">
        <a href="/" class="text-white nav-link transition duration-200">Home</a>
        <div class="nav-link-hr"></div>
    </div>

    <div class="nav-link-container dropdown">
        <a href="#" class="text-white nav-link transition duration-200" style="display: flex; align-items: center;">
            Club <img src="{{ asset('bas.png') }}" alt="▼" style="width: 24px; height: 24px; margin-left: 5px;">
        </a>
        <div class="nav-link-hr"></div>
        <div class="dropdown-content">
        <a href="{{ route('about.index') }}">About</a>
            <a href="{{ route('clubinfo') }}">News</a>
            <a href="{{ route('press_releases.index') }}">Press Releases</a>
            <a href="{{ route('galleries.index') }}">Gallery</a>
            <a href="{{ route('videos.index') }}">Videos</a>
        </div>
    </div>

    <div class="nav-link-container">
        <a href="{{ route('calendar.show') }}" class="text-white nav-link transition duration-200">Competition</a>
        <div class="nav-link-hr"></div>
    </div>

    <div class="dropdown nav-link-container">
        <a href="{{ route('teams') }}" class="text-white nav-link transition duration-200" style="display: flex; align-items: center;">
            Teams <img src="{{ asset('bas.png') }}"  alt="▼" style="width: 24px; height: 24px; margin-left: 5px;">
        </a>
        <div class="nav-link-hr"></div>
        <div class="dropdown-content">
            <a href="{{ route('teams') }}">Senior</a>
            <a href="{{ route('playersu21.index') }}">U21</a>
        </div>
    </div>

    <div class="nav-link-container">
        <a href="{{ route('sponsors.index') }}" class="text-white nav-link transition duration-200">Sponsors</a>
        <div class="nav-link-hr"></div>
    </div>

    <div class="nav-link-container">
        <a href="{{ route('contact.show') }}" class="text-white nav-link transition duration-200">Contact</a>
        <div class="nav-link-hr"></div>
    </div>

    <div class="nav-link-container">
        <a href="{{ route('fanshop.index') }}" class="text-white nav-link transition duration-200">Fanshop</a>
        <div class="nav-link-hr"></div>
    </div>

    @auth
    <div class="nav-link-container">
        <a href="{{ url('/dashboard') }}" class="text-white nav-link transition duration-200 px-4 border-2 rounded-full" style="border-color: {{ $secondaryColor }};">
            Dashboard
        </a>
    </div>
    @endauth
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
        
        <!-- Bouton de fermeture -->
        <button id="close-button" class="text-white">&times;</button>
        
        <!-- Menu -->
        <a href="/" class="block text-white py-2">Home</a>

        <!-- Club Dropdown -->
        <a href="#" class="block text-white py-2" onclick="toggleDropdown('clubDropdown')" style="display: flex; align-items: center;">
    Club <img src="{{ asset('bas.png') }}"  alt="▼" style="width: 24px; height: 24px; margin-left: 5px;">
</a>
<div id="clubDropdown" class="hidden pl-4">
<a href="{{ route('about.index') }}" class="block text-white py-2">About</a>
    <a href="{{ route('clubinfo') }}" class="block text-white py-2">News</a>
    <a href="{{ route('press_releases.index') }}" class="block text-white py-2">Press Releases</a>
    <a href="{{ route('galleries.index') }}" class="block text-white py-2">Gallery</a>
    <a href="{{ route('videos.index') }}" class="block text-white py-2">Videos</a>
</div>

<!-- Calendar -->
<a href="{{ route('calendar.show') }}" class="block text-white py-2">Competition</a>

<!-- Teams Dropdown -->
<a href="#" class="block text-white py-2" onclick="toggleDropdown('teamsDropdown')" style="display: flex; align-items: center;">
    Teams <img src="{{ asset('bas.png') }}"  alt="▼" style="width: 24px; height: 24px; margin-left: 5px;">
</a>
<div id="teamsDropdown" class="hidden pl-4">
    <a href="{{ route('teams') }}" class="block text-white py-2">Senior</a>
    <a href="{{ route('playersu21.index') }}" class="block text-white py-2">U21</a>
</div>

<a href="{{ route('sponsors.index') }}" class="block text-white py-2">Sponsors</a>
<a href="{{ route('contact.show') }}" class="block text-white py-2">Contact</a>
<a href="{{ route('fanshop.index') }}" class="block text-white py-2">Fanshop</a>
@auth
<a href="{{ url('/dashboard') }}" class="block text-white py-2">Dashboard</a>
@endauth
    </div>
</div>

<div id="scroll-to-top" style="display: none; position: fixed; bottom: 20px; right: 20px; z-index: 1000; cursor: pointer;">
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
        mobileMenuModal.classList.remove('hidden');
        mobileMenuModal.classList.add('open');
    });

    closeButton.addEventListener('click', function () {
        mobileMenuModal.classList.remove('open');
        setTimeout(() => {
            mobileMenuModal.classList.add('hidden');
        }, 300); // Match this with your transition duration
    });
});

function toggleDropdown(dropdownId) {
        console.log('Toggling: ', dropdownId); // Pour débogage
        var dropdown = document.getElementById(dropdownId);
        dropdown.classList.toggle('hidden');
    }

     // Fermer le modal si on clique en dehors
     document.getElementById('mobile-menu-modal').addEventListener('click', function(e) {
        if (e.target.id === 'mobile-menu-modal') {
            closeMobileMenu();
        }
    });

    function closeMobileMenu() {
        document.getElementById('mobile-menu-modal').classList.remove('open');
    }
</script>