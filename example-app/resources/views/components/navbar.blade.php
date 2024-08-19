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

    /* Dropdown Styles */
    .dropdown {
        position: relative;
        display: inline-block;
        transition: transform 0.3s ease-in-out;
        z-index: 1050;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        background-color: white;
        z-index: 1100; 
        min-width: 160px;
        box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
        text-align: left;
        opacity: 0;
        transform: translateY(10px);
        transition: opacity 0.3s ease, transform 0.3s ease;
        border-radius: 8px;
        overflow: hidden;
    }

    .dropdown-content a {
        color: {{ $primaryColor }};
        padding: 12px 16px;
        text-decoration: none;
        display: block;
        transition: background-color 0.3s ease, color 0.3s ease;
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

    /* Add a subtle shadow to the dropdown */
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
        border-bottom: 10px solid white;
        z-index: -1;
    }

    .dropdown-content a:not(:last-child) {
        border-bottom: 1px solid #e5e5e5;
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
            <div class="ml-3 text-white px-2 py-1 border-4" style="border-color: {{ $secondaryColor }};">
                {{ $clubName }}
            </div>
        </div>

        <!-- Navigation links (hidden on mobile, shown on desktop) -->
        <div class="desktop-nav space-x-8">
            <a href="/" class="text-white nav-link transition duration-200">Home</a>
            <div class="dropdown">
                <a href="#" class="text-white nav-link transition duration-200">Club▼</a>
                <div class="dropdown-content">
                    <a href="{{ route('clubinfo') }}">News</a>
                    <a href="{{ route('about.index') }}">About</a>
                </div>
            </div>
            <a href="{{ route('calendar.show') }}" class="text-white nav-link transition duration-200">Calendar</a>
            <div class="dropdown">
                <a href="{{ route('teams') }}" class="text-white nav-link transition duration-200">Teams▼</a>
                <div class="dropdown-content">
                    <a href="{{ route('teams') }}">Senior</a>
                    <a href="#">U21</a>
                </div>
            </div>
            <a href="{{ route('sponsors.index') }}" class="text-white nav-link transition duration-200">Sponsors</a>
            <a href="{{ route('contact.show') }}" class="text-white nav-link transition duration-200">Contact</a>
            <a href="{{ route('fanshop.index') }}" class="text-white nav-link transition duration-200">Fanshop</a>
            @auth
                <a href="{{ url('/dashboard') }}" class="text-white nav-link transition duration-200 px-4 border-2 rounded-full" style="border-color: {{ $secondaryColor }};">
                    Dashboard
                </a>
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
        <a href="#" class="block text-white py-2" onclick="toggleDropdown('clubDropdown')">Club ▼</a>
        <div id="clubDropdown" class="hidden pl-4">
            <a href="{{ route('clubinfo') }}" class="block text-white py-2">News</a>
            <a href="{{ route('about.index') }}" class="block text-white py-2">About</a>
        </div>

        <!-- Calendar -->
        <a href="{{ route('calendar.show') }}" class="block text-white py-2">Calendar</a>

        <!-- Teams Dropdown -->
        <a href="#" class="block text-white py-2" onclick="toggleDropdown('teamsDropdown')">Teams ▼</a>
        <div id="teamsDropdown" class="hidden pl-4">
            <a href="{{ route('teams') }}" class="block text-white py-2">Senior</a>
            <a href="#" class="block text-white py-2">U21</a>
        </div>

        <a href="{{ route('sponsors.index') }}" class="block text-white py-2">Sponsors</a>
        <a href="{{ route('contact.show') }}" class="block text-white py-2">Contact</a>
        <a href="{{ route('fanshop.index') }}" class="block text-white py-2">Fanshop</a>
        <a href="{{ url('/dashboard') }}" class="block text-white py-2">Dashboard</a>
    </div>
</div>

@if(session('success'))
    <div id="success-alert" class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<script>
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