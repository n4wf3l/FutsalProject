@php
    use Illuminate\Support\Facades\Route;
@endphp

<head>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css"/>
</head>
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

    .sponsor-carousel-container {
        width: 40%;
        margin: 0 auto;
        text-align: center;
        margin-bottom: 80px;
    }

    .sponsor-carousel-container h2 {
        font-size: 2rem;
        font-weight: bold;
        margin-bottom: 20px;
    }

    .carousel-wrapper {
        position: relative;
        overflow: hidden;
        width: 100%;
    }

    .carousel {
        display: flex;
        transition: transform 0.3s ease-in-out;
        width: 100%; 
    }

    .carousel-item {
        min-width: 33.33%; 
        padding: 10px;
        display: flex;
        justify-content: center;
        align-items: center;
        box-sizing: border-box;
    }

    .carousel-item img {
        max-height: 100px;
        width: auto;
    }

    .carousel-button {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background-color: rgba(0, 0, 0, 0.5);
        color: white;
        border: none;
        padding: 10px;
        cursor: pointer;
    }

    .carousel-button.prev {
        left: 0;
    }

    .carousel-button.next {
        right: 0;
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

<div class="sponsor-carousel-container">
    <x-page-title>
        Sponsors and partners
    </x-page-title>
    <div class="carousel-wrapper">
        <div class="carousel">
            @foreach($sponsors as $sponsor)
                <div class="carousel-item">
                    <img src="{{ asset('storage/' . $sponsor->logo) }}" alt="{{ $sponsor->name }}">
                </div>
            @endforeach
            <!-- Duplicate the items for the infinite loop effect -->
            @foreach($sponsors as $sponsor)
                <div class="carousel-item">
                    <img src="{{ asset('storage/' . $sponsor->logo) }}" alt="{{ $sponsor->name }}">
                </div>
            @endforeach
        </div>
        <button class="carousel-button prev">&#10094;</button>
        <button class="carousel-button next">&#10095;</button>
    </div>
</div>

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
                <h3 class="font-bold mb-4">CONTACT {{ $clubName }}</h3>
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

<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
<script type="text/javascript">
 document.addEventListener('DOMContentLoaded', () => {
    const carousel = document.querySelector('.carousel');
    const carouselItems = document.querySelectorAll('.carousel-item');
    const prevButton = document.querySelector('.carousel-button.prev');
    const nextButton = document.querySelector('.carousel-button.next');
    const itemWidth = carouselItems[0].offsetWidth;
    let currentIndex = 0;
    let autoPlayInterval;

    // Dupliquer les premiers et derniers éléments pour créer l'effet de boucle infinie
    const firstClone = carouselItems[0].cloneNode(true);
    const lastClone = carouselItems[carouselItems.length - 1].cloneNode(true);

    carousel.appendChild(firstClone);
    carousel.insertBefore(lastClone, carouselItems[0]);

    const totalItems = carousel.querySelectorAll('.carousel-item').length;

    // Initialiser la position du carrousel pour ne pas afficher le clone de fin en premier
    carousel.style.transform = `translateX(${-itemWidth}px)`;

    function updateCarousel() {
        const translateX = -(currentIndex + 1) * itemWidth;
        carousel.style.transition = 'transform 0.3s ease-in-out';
        carousel.style.transform = `translateX(${translateX}px)`;
    }

    function showNext() {
        currentIndex++;
        updateCarousel();
        if (currentIndex === totalItems - 2) {
            // Transition vers le premier élément
            setTimeout(() => {
                carousel.style.transition = 'none';
                currentIndex = 0;
                carousel.style.transform = `translateX(${-itemWidth}px)`;
            }, 300); // Correspond au temps de transition défini
        }
    }

    function showPrev() {
        currentIndex--;
        updateCarousel();
        if (currentIndex < 0) {
            // Transition vers le dernier élément
            setTimeout(() => {
                carousel.style.transition = 'none';
                currentIndex = totalItems - 3;
                carousel.style.transform = `translateX(${-currentIndex * itemWidth}px)`;
            }, 300); // Correspond au temps de transition défini
        }
    }

    function startAutoPlay() {
        autoPlayInterval = setInterval(showNext, 2000);
    }

    function stopAutoPlay() {
        clearInterval(autoPlayInterval);
    }

    nextButton.addEventListener('click', () => {
        stopAutoPlay();
        showNext();
        startAutoPlay();
    });

    prevButton.addEventListener('click', () => {
        stopAutoPlay();
        showPrev();
        startAutoPlay();
    });

    startAutoPlay();
});
</script>
