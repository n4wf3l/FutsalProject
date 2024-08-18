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
    }

    .dropdown-content {
        display: none;
        position: absolute;
        background-color: white;
        min-width: 160px;
        box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
        z-index: 1;
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

        <!-- Navigation links -->
        <div class="flex space-x-8">
            <a href="/" class="text-white nav-link transition duration-200">Home</a>

            <!-- Dropdown for Club -->
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
            <a href="{{ route('contact') }}" class="text-white nav-link transition duration-200">Contact</a>
            <a href="{{ route('fanshop.index') }}" class="text-white nav-link transition duration-200">Fanshop</a>
            @if (route('login'))
                @auth
                <a href="{{ url('/dashboard') }}" class="text-white nav-link transition duration-200 px-4 border-2 rounded-full" style="border-color: {{ $secondaryColor }};">
    Dashboard
</a>
                @endauth
            @endif
        </div>

        <!-- Authentication links -->
    </div>
</nav>
