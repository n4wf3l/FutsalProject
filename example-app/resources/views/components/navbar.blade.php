@php
    $primaryColor = $primaryColor ?? '#1F2937'; // Gris par défaut
    $secondaryColor = $secondaryColor ?? '#FF0000'; // Rouge par défaut
    $logoPath = $logoPath ?? null;
    $clubName = $clubName ?? 'Default Club Name';
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
    }

    .dropdown-content {
        display: none;
        position: absolute;
        background-color: white;
        min-width: 160px;
        box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
        z-index: 1;
        text-align: left;
    }

    .dropdown-content a {
        color: {{ $primaryColor }};
        padding: 12px 16px;
        text-decoration: none;
        display: block;
    }

    .dropdown-content a:hover {
        background-color: {{ $secondaryColor }};
        color: white;
    }

    .dropdown:hover .dropdown-content {
        display: block;
    }
</style>

<nav class="p-4" style="background-color: {{ $primaryColor }};">
    <div class="container mx-auto flex justify-between items-center custom-font" style="font-size: 25px; letter-spacing: 2px;">
        <!-- Logo -->
        <div class="flex items-center">
            @if($logoPath)
                <img src="{{ $logoPath }}" alt="Site Logo" style="height: 40px; width: auto;">
            @else
                <p>Logo non disponible</p>
            @endif
            <div class="ml-3 text-white">
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

            <a href="{{ route('calendar') }}" class="text-white nav-link transition duration-200">Calendar</a>
            <a href="{{ route('teams') }}" class="text-white nav-link transition duration-200">Teams</a>
            <a href="{{ route('sponsors.index') }}" class="text-white nav-link transition duration-200">Sponsors</a>
            <a href="{{ route('contact') }}" class="text-white nav-link transition duration-200">Contact</a>
            <a href="{{ route('fanshop') }}" class="text-white nav-link transition duration-200">Fanshop</a>
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="text-white nav-link transition duration-200">Dashboard</a>
                @endauth
            @endif
        </div>

        <!-- Authentication links -->
        <div class="flex space-x-4">
            @if (Route::has('login'))
                @auth
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center text-white transition duration-200" style="color: white;" onmouseover="this.style.color='{{ $secondaryColor }}'" onmouseout="this.style.color='white'">
                        <img src="{{ asset('logout.png') }}" alt="Logout Icon" class="h-4 w-4">
                        <span class="ml-3">LOG OUT</span>
                    </button>
                </form>
                @else
                    <a href="{{ route('login') }}" class="text-white hover:text-white transition duration-200">
                        Log in |
                    </a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="text-white hover:text-white transition duration-200">
                            Register
                        </a>
                    @endif
                @endauth
            @endif
        </div>
    </div>
</nav>
