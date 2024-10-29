
@php
    use App\Models\ClubInfo;
    use Illuminate\Support\Facades\Route;

    $clubInfo = ClubInfo::first();
    $federationLogo = $clubInfo && $clubInfo->federation_logo ? asset('storage/' . $clubInfo->federation_logo) : asset('unknown.png');
    $organizationLogo = $clubInfo && $clubInfo->organization_logo ? asset('storage/' . $clubInfo->organization_logo) : null;
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

    .carousel-item {
        flex: 0 0 25%; /* Prend 25% de la largeur en bureau */
        padding: 10px;
    }

    .carousel-item img {
        max-width: 100%; /* S'assure que les images ne débordent pas */
        height: auto;
        margin: 0 auto;
    }

    .sponsor-carousel-container {
    width: 40%;
    margin: 0 auto;
    text-align: center;
    margin-bottom: 80px;
}



    @media (max-width: 767px) {
        .carousel-item {
            min-width: 100%; /* Chaque item prend la largeur de l'écran en mobile */
            padding: 10%;
        }
        .carousel-item img {
            max-width: 100%;
            height: auto;
        }

    }
</style>

@if($sponsors->isNotEmpty())
    <div class="sponsor-carousel-container">
        <!-- Application du style en ligne pour le titre -->
        <x-page-title style="font-size: 1.2rem; font-weight: bold; text-align: center; white-space: nowrap;">
            {{ __('messages.sponsors_partners') }}
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
@endif


<footer class="text-white py-12 mt-auto" style="background-color: {{ $primaryColor }};">
    <div class="footer-center container mx-auto px-6">
        <div class="flex flex-wrap justify-between">
        <div class="w-full md:w-1/4 mb-6 md:mb-0 footer-logo">
    <div class="flex items-center space-x-4 mb-6">
        <!-- Affiche le logo du club ou unknown.png si indisponible -->
        <img src="{{ $logoPath ? asset($logoPath) : asset('unknown.png') }}" 
             alt="@lang('messages.logo_not_available')" 
             style="height: 60px; width: auto;">

        <!-- Affiche le logo de la fédération ou unknown.png si indisponible -->
        <img src="{{ $federationLogo ? asset($federationLogo) : asset('unknown.png') }}" 
             alt="@lang('messages.logo_not_available')" 
             style="height: 60px; width: auto;">

        <!-- Affiche le logo de l'organisation uniquement s'il est disponible -->
        @if ($organizationLogo)
    <img src="{{ $organizationLogo }}" 
         alt="" 
         style="height: 60px; width: auto;">
@endif
    </div>
</div>



            <!-- Links -->
            <div class="w-full md:w-1/4 mb-6 md:mb-0 footer-links">
                <h3 class="font-bold mb-4">@lang('messages.about_us')</h3>
                <ul class="space-y-2">
                    <li><a href="{{ route('clubinfo') }}" class="hover:text-gray-300">@lang('messages.news')</a></li>
                    <li><a href="{{ route('about.index') }}" class="hover:text-gray-300">@lang('messages.about')</a></li>
                    <li><a href="{{ route('about.index') }}" class="hover:text-gray-300">@lang('messages.sports_hall')</a></li>
                    <li><a href="{{ route('about.index') }}" class="hover:text-gray-300">@lang('messages.board')</a></li>
                    <li><a href="{{ route('about.index') }}#documents-section" class="hover:text-gray-300">@lang('messages.regulations')</a></li>
                    <li>
                        @if (Route::has('login'))
                            @auth
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="hover:text-gray-300 transition duration-200">
                                        @lang('messages.logout')
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="hover:text-gray-300 transition duration-200">
                                    @lang('messages.authentication')
                                </a>
                            @endauth
                        @endif
                    </li>
                </ul>
            </div>

            <!-- Team Links -->
            <div class="w-full md:w-1/4 mb-6 md:mb-0 footer-links">
                <h3 class="font-bold mb-4">@lang('messages.team')</h3>
                <ul class="space-y-2">
                    <li><a href="{{ route('teams') }}" class="hover:text-gray-300">@lang('messages.elite')</a></li>
                    <li><a href="{{ route('playersu21.index') }}" class="hover:text-gray-300">@lang('messages.u21')</a></li>
                    <li><a href="{{ route('teams') }}#staff-section" class="hover:text-gray-300">@lang('messages.technical_medical_staff')</a></li>
                    <li><a href="{{ route('about.index') }}" class="hover:text-gray-300">@lang('messages.employees')</a></li>
                    <li><a href="{{ route('about.index') }}" class="hover:text-gray-300">@lang('messages.youth_development')</a></li>
                </ul>
            </div>

            <!-- Matches -->
            <div class="w-full md:w-1/4 mb-6 md:mb-0 footer-links">
                <h3 class="font-bold mb-4">@lang('messages.matches')</h3>
                <ul class="space-y-2">
                    <li><a href="{{ route('calendar.show', ['team_filter' => 'specific_team', 'date_filter' => 'upcoming']) }}#calendar-section" class="hover:text-gray-300">@lang('messages.next_matches')</a></li>
                    <li><a href="{{ route('calendar.show', ['team_filter' => 'specific_team', 'date_filter' => 'results']) }}#calendar-section" class="hover:text-gray-300">@lang('messages.results')</a></li>
                    <li><a href="{{ route('calendar.show') }}#ranking-section" class="hover:text-gray-300">@lang('messages.general_calendar')</a></li>
                </ul>
            </div>

            <!-- Contact Information -->
            <div class="w-full md:w-1/4 footer-contact">
                <h3 class="font-bold mb-4">@lang('messages.contact')</h3>
                <ul class="space-y-2">
                    <li class="footer-logo flex items-center">
                        <img src="{{ asset('position.png') }}" alt="@lang('messages.location')" class="h-6 w-6 mr-2"> 
                        <span>{{ $clubLocation }}</span>
                    </li>
                    <li class="footer-logo flex items-center">
                        <img src="{{ asset('tel.png') }}" alt="Tel" class="h-6 w-6 mr-2">
                        <span>@lang('messages.gc'): {{ $phone }}</span>
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
                                         <!-- LinkedIn -->
    <a href="https://www.linkedin.com/company/dina-k%C3%A9nitra-futsal-club/" class="text-white hover:text-gray-300 flex items-center">
        <img src="{{ asset('linkedin.png') }}" alt="LinkedIn" class="h-6 w-6 mr-2"> 
    </a>
    
    <!-- YouTube -->
    <a href="https://www.youtube.com/@DINAFUTSAL" class="text-white hover:text-gray-300 flex items-center">
        <img src="{{ asset('youtube.png') }}" alt="YouTube" class="h-6 w-6 mr-2"> 
    </a>
                </div>
            </div>
        </div>

       <!-- Footer Links and Copyright -->
       <div class="mt-8 border-t border-gray-500 pt-6 footer-copyright">
            <p>&copy; 2024 {{ $clubName }} - @lang('messages.platform_by') <a href="https://nainnovations.be/" class="hover:text-gray-300" target="_blank">NA Innovations</a></p>
            <div class="mt-4 md:mt-0 flex space-x-4 footer-links-container">
    <a href="{{ route('legal') }}#privacy-policy" class="hover:text-gray-300">@lang('messages.privacy')</a>
    <a href="{{ route('legal') }}#terms-conditions" class="hover:text-gray-300">@lang('messages.terms_conditions')</a>
    <a href="{{ route('legal') }}#cookies-policy" class="hover:text-gray-300">@lang('messages.cookies')</a>
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
