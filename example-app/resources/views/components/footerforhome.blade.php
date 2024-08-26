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
        .footer-center {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .footer-logo,
        .footer-social,
        .footer-links-container {
            justify-content: center;
            display: flex;
            width: 100%;
        }

        .footer-links,
        .footer-contact {
            text-align: center;
        }

        .footer-copyright {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .footer-copyright .footer-links-container {
            margin-top: 1rem;
            display: flex;
            flex-direction: row;
            justify-content: center;
            gap: 10px;
        }
    }
</style>

<footer class="text-white py-12 mt-auto" style="background-color: {{ $primaryColor }};">
    <div class="footer-center container mx-auto px-6">
        <div class="flex flex-wrap justify-between">
            <!-- Logo and Social Media -->
            <div class="w-full md:w-1/4 mb-6 md:mb-0 footer-logo">
                <div class="flex items-center space-x-4 mb-6">
                    <img src="{{ $logoPath }}" alt="Club Logo" style="height: 60px; width: auto;">
                    <img src="{{ $federationLogo }}" alt="Fed Logo" style="height: 60px; width: auto;">
                </div>
            </div>

            <!-- Links -->
            <div class="w-full md:w-1/4 mb-6 md:mb-0 footer-links">
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

            <!-- Team Links -->
            <div class="w-full md:w-1/4 mb-6 md:mb-0 footer-links">
                <h3 class="font-bold mb-4">TEAM</h3>
                <ul class="space-y-2">
                    <li><a href="{{ route('teams') }}" class="hover:text-gray-300">Elite</a></li>
                    <li><a href="{{ route('playersu21.index') }}" class="hover:text-gray-300">U21</a></li>
                    <li><a href="#" class="hover:text-gray-300">Technical & Medical Staff</a></li>
                    <li><a href="#" class="hover:text-gray-300">Employees</a></li>
                    <li><a href="#" class="hover:text-gray-300">Youth Development</a></li>
                </ul>
            </div>

            <!-- Matches -->
            <div class="w-full md:w-1/4 mb-6 md:mb-0 footer-links">
                <h3 class="font-bold mb-4">MATCHES</h3>
                <ul class="space-y-2">
                    <li><a href="{{ route('calendar.show', ['team_filter' => 'specific_team', 'date_filter' => 'upcoming']) }}#calendar-section" class="hover:text-gray-300">Next Matches</a></li>
                    <li><a href="{{ route('calendar.show', ['team_filter' => 'specific_team', 'date_filter' => 'results']) }}#calendar-section" class="hover:text-gray-300">Results</a></li>
                    <li><a href="{{ route('calendar.show') }}#ranking-section" class="hover:text-gray-300">General Calendar</a></li>
                </ul>
            </div>

            <!-- Contact Information -->
            <div class="w-full md:w-1/4 footer-contact">
                <h3 class="font-bold mb-4">CONTACT</h3>
                <ul class="space-y-2">
                    <li class="footer-logo flex items-center">
                        <img src="{{ asset('position.png') }}" alt="Position" class="h-6 w-6 mr-2"> 
                        <span>{{ $clubLocation }}</span>
                    </li>
                    <li class="footer-logo flex items-center">
                        <img src="{{ asset('tel.png') }}" alt="Tel" class="h-6 w-6 mr-2">
                        <span>GC: {{ $phone }}</span>
                    </li>
                    <li class="footer-logo flex items-center">
                        <img src="{{ asset('email.png') }}" alt="Email" class="h-6 w-6 mr-2"> 
                        <span>{{ $email }}</span>
                    </li>
                </ul>

                <div class="footer-social flex space-x-4 mt-4">
                    <a href="{{ $facebook }}" class="text-white hover:text-gray-300 flex items-center">
                        <img src="{{ asset('facebook.png') }}" alt="Facebook" class="h-6 w-6 mr-2"> 
                    </a>
                    <a href="{{ $instagram }}" class="text-white hover:text-gray-300 flex items-center">
                        <img src="{{ asset('instagram.png') }}" alt="Instagram" class="h-6 w-6 mr-2"> 
                    </a>
                </div>
            </div>
        </div>

        <!-- Footer Links and Copyright -->
        <div class="mt-8 border-t border-gray-500 pt-6 footer-copyright">
            <p>&copy; 2024 {{ $clubName }} - Website by <a href="https://nainnovations.be/" class="hover:text-white" target="_blank">NA Innovations</a></p>
            <div class="mt-4 md:mt-0 flex space-x-4 footer-links-container">
                <a href="#" class="hover:text-white">Privacy</a>
                <a href="#" class="hover:text-white">Terms & Conditions</a>
                <a href="#" class="hover:text-white">Cookies</a>
            </div>
        </div>
    </div>
</footer>
