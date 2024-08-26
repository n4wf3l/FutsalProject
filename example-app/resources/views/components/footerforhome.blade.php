@php
    use Illuminate\Support\Facades\Route;
@endphp

<style>
    html, body {
        height: 100%;
        margin: 0;
        display: flex;
        flex-direction: column;
    }

    body {
        /* This will allow the content to take up the remaining space */
        display: flex;
        flex-direction: column;
    }

    main {
        flex: 1;
    }

    footer {
        margin-top: auto;
    }

    @media (max-width: 767px) {
        .flex {
        flex-direction: column;
        align-items: center;
    }

    .w-full {
        width: 100%;
        text-align: center;
    }

    /* Aligner les logos du club et de la fédération côte à côte */
    .flex.items-center.space-x-4.mb-6 {
        display: flex;
        flex-direction: row;
        justify-content: center;
        gap: 10px;
    }

    /* Alignement horizontal des icônes de contact et du texte */
    .flex.items-center {
        display: flex;
        flex-direction: row;
        justify-content: center;
        gap: 10px;
    }

    /* Réseaux sociaux côte à côte */
    .flex.space-x-4.mt-4 {
        display: flex;
        flex-direction: row;
        justify-content: center;
        gap: 15px;
    }

    /* Ajustement du footer */
    .footer-bottom {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        width: 100%;
    }

    .footer-bottom div {
        order: 1;
        margin-top: 0;
        margin-bottom: 1rem;
    }

    .footer-bottom p {
        order: 2;
        margin-top: 1rem;
    }
    .footer-links {
        margin-bottom: 1rem;
    }

    .footer-copyright {
        margin-top: 10px;
    }

    .footer-links .flex {
    display: flex;
    justify-content: center;
    flex-direction: row;
    gap: 20px;
    flex-wrap: wrap;
}

}
</style>


<footer class="text-white py-12 mt-auto" style="background-color: {{ $primaryColor }};">
    <div class="container mx-auto px-6">
        <div class="flex flex-wrap justify-between">
            <!-- Logo and Social Media -->
            <div class="w-full md:w-1/4 mb-6 md:mb-0">
                <div class="flex items-center space-x-4 mb-6">
                    <img src="{{ $logoPath }}" alt="Club Logo" style="height: 60px; width: auto;">
                    <img src="{{ $federationLogo }}" alt="Fed Logo" style="height: 60px; width: auto;">
                </div>
            </div>

            <!-- Links -->
            <div class="w-full md:w-1/4 mb-6 md:mb-0">
                <h3 class="font-bold mb-4">ABOUT US</h3>
                <ul class="space-y-2">
                    <li><a href="{{ route('clubinfo') }}" class="hover:text-gray-300">News</a></li>
                    <li><a href="{{ route('about.index') }}" class="hover:text-gray-300">About</a></li>
                    <li><a href="#" class="hover:text-gray-300">Sports Hall</a></li>
                    <li><a href="#" class="hover:text-gray-300">Board</a></li>
                    <li><a href="#" class="hover:text-gray-300">Regulations</a></li>
                    <li>
                        @if (Route::has('login'))
                            @auth
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="hover:text-gray-300 transition duration-200">
                                        Log Out
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="hover:text-gray-300 transition duration-200">
                                    Authentication
                                </a>
                            @endauth
                        @endif
                    </li>
                </ul>
            </div>

            <div class="w-full md:w-1/4 mb-6 md:mb-0">
                <h3 class="font-bold mb-4">TEAM</h3>
                <ul class="space-y-2">
                    <li><a href="{{ route('teams') }}" class="hover:text-gray-300">Elite</a></li>
                    <li><a href="{{ route('playersu21.index') }}" class="hover:text-gray-300">U21</a></li>
                    <li><a href="#" class="hover:text-gray-300">Technical & Medical Staff</a></li>
                    <li><a href="#" class="hover:text-gray-300">Employees</a></li>
                    <li><a href="#" class="hover:text-gray-300">Youth Development</a></li>
                </ul>
            </div>

            <div class="w-full md:w-1/4 mb-6 md:mb-0">
                <h3 class="font-bold mb-4">MATCHES</h3>
                <ul class="space-y-2">
                    <li><a href="{{ route('calendar.show', ['team_filter' => 'specific_team', 'date_filter' => 'upcoming']) }}#calendar-section" class="hover:text-gray-300">Next Matches</a></li>
                    <li><a href="{{ route('calendar.show', ['team_filter' => 'specific_team', 'date_filter' => 'results']) }}#calendar-section" class="hover:text-gray-300">Results</a></li>
                    <li><a href="{{ route('calendar.show') }}#ranking-section" class="hover:text-gray-300">General Calendar</a></li>
                </ul>
            </div>

            <!-- Contact Information -->
            <div class="w-full md:w-1/4">
                <h3 class="font-bold mb-4">CONTACT</h3>
                <ul class="space-y-2">
                    <li class="flex items-center">
                        <img src="{{ asset('position.png') }}" alt="Position" class="h-6 w-6 mr-2">
                        <span>{{ $clubLocation }}</span>
                    </li>
                    <li class="flex items-center">
                        <img src="{{ asset('tel.png') }}" alt="Tel" class="h-6 w-6 mr-2">
                        <span>GC: {{ $phone }}</span>
                    </li>
                    <li class="flex items-center">
                        <img src="{{ asset('email.png') }}" alt="Email" class="h-6 w-6 mr-2">
                        <span>{{ $email }}</span>
                    </li>
                </ul>

                <div class="flex space-x-4 mt-4">
                    <a href="{{ $facebook }}" class="text-white hover:text-gray-300 flex items-center">
                        <img src="{{ asset('facebook.png') }}" alt="Facebook" class="h-6 w-6 mr-2">
                    </a>
                    <a href="{{ $instagram }}" class="text-white hover:text-gray-300 flex items-center">
                        <img src="{{ asset('instagram.png') }}" alt="Instagram" class="h-6 w-6 mr-2">
                    </a>
                </div>
            </div>
        </div>

        <!-- Footer links and copyright -->
        <div class="footer-links text-center mt-8 border-t border-gray-500 pt-6">
            <div class="flex justify-center space-x-4">
                <a href="#" class="hover:text-white">Privacy</a>
                <a href="#" class="hover:text-white">Terms & Conditions</a>
                <a href="#" class="hover:text-white">Cookies</a>
            </div>
        </div>
        <div class="footer-copyright text-center mt-4">
            <p>&copy; 2024 {{ $clubName }} - Website by <a href="https://nainnovations.be/" class="hover:text-white" target="_blank">NA Innovations</a></p>
        </div>
    </div>
</footer>

