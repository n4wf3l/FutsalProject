
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
        transition: transform 0.3s ease;
    }

    .carousel-item img:hover {
        transform: scale(1.1);
    }

    .carousel-button {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        color: {{ $secondaryColor }};
        border: none;
        background: none;
        font-size: 2rem;
        cursor: pointer;
    }

    .carousel-button.prev {
        left: 0;
    }

    .carousel-button.next {
        right: 0;
    }

    .responsive-hr {
      display: none; /* Masque le <hr> par défaut */
  }

  /* Le rend visible uniquement pour les écrans de largeur maximale 768px */
  @media (max-width: 768px) {
      .responsive-hr {
          display: block;
          border: 1px solid #ccc; /* Style du <hr>, modifie selon tes préférences */
          margin: 16px 0;
          width: 100%;
      }
  }

    @media (max-width: 767px) {

        .sponsor-carousel-container h2 {
        font-size: 1rem;
        margin-top: 15px;
        font-weight: bold;
        margin-bottom: 20px;
    }

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

    .carousel-item img,
    .footer-social img {
        transition: transform 0.3s ease; /* Animation pour le survol */
    }

    .carousel-item img:hover,
    .footer-social img:hover {
        transform: scale(1.1); /* Agrandit l'image à 110% */
    }
</style>

@if($sponsors->isNotEmpty())
    <div class="sponsor-carousel-container mx-auto mb-8 text-center">
        <div class="flex justify-center items-center space-x-3">
            <!-- Barre verticale -->
            <div class="w-1 h-10 bg-secondary-color"></div>

            <!-- Texte centré et redimensionné -->
            <x-page-title class="text-2xl font-bold sm:text-lg">
                {{ __('messages.sponsors_partners') }}
            </x-page-title>
        </div>

        <div class="carousel-wrapper">
            <div class="carousel">
                @php
                    $totalImages = count($sponsors);
                    if ($totalImages === 1) {
                        // Si une seule image, quadrupler pour obtenir 4 éléments
                        $displayedSponsors = array_fill(0, 4, $sponsors[0]);
                    } elseif ($totalImages === 2) {
                        // Si deux images, les dupliquer pour obtenir 4 éléments
                        $displayedSponsors = array_merge($sponsors->all(), $sponsors->all());
                    } else {
                        // Plus de 2 images, les afficher normalement puis dupliquer
                        $displayedSponsors = array_merge($sponsors->all(), $sponsors->all());
                    }
                @endphp

                @foreach($displayedSponsors as $sponsor)
                    <div class="carousel-item">
                        <a href="{{ $sponsor->website }}" target="_blank" rel="noopener noreferrer">
                            <img src="{{ asset('storage/' . $sponsor->logo) }}" alt="{{ $sponsor->name }}" loading="lazy">
                        </a>
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

            <hr class="responsive-hr">
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

                        <hr class="responsive-hr">
            <!-- Matches -->
            <div class="w-full md:w-1/4 mb-6 md:mb-0 footer-links">
                <h3 class="font-bold mb-4">@lang('messages.matches')</h3>
                <ul class="space-y-2">
                    <li><a href="{{ route('calendar.show', ['team_filter' => 'specific_team', 'date_filter' => 'upcoming']) }}#calendar-section" class="hover:text-gray-300">@lang('messages.next_matches')</a></li>
                    <li><a href="{{ route('calendar.show', ['team_filter' => 'specific_team', 'date_filter' => 'results']) }}#calendar-section" class="hover:text-gray-300">@lang('messages.results')</a></li>
                    <li><a href="{{ route('calendar.show') }}#ranking-section" class="hover:text-gray-300">@lang('messages.general_calendar')</a></li>
                </ul>
            </div>

            <hr class="responsive-hr">
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
    const prevButton = document.querySelector('.carousel-button.prev');
    const nextButton = document.querySelector('.carousel-button.next');
    const carouselItems = Array.from(document.querySelectorAll('.carousel-item'));
    const itemWidth = carouselItems[0].offsetWidth;
    let currentIndex = 0;
    let isTransitioning = false;
    let autoPlayInterval;

    // Duplique les premiers et derniers éléments pour créer l'illusion de boucle infinie
    carouselItems.forEach(item => {
        const cloneStart = item.cloneNode(true);
        const cloneEnd = item.cloneNode(true);
        carousel.appendChild(cloneEnd);
        carousel.insertBefore(cloneStart, carousel.firstChild);
    });

    const totalItems = carousel.querySelectorAll('.carousel-item').length;

    // Position initiale du carrousel
    carousel.style.transform = `translateX(${-itemWidth * carouselItems.length}px)`;

    function updateCarousel(direction = 1) {
        if (!isTransitioning) {
            isTransitioning = true;
            currentIndex += direction;
            carousel.style.transition = 'transform 0.3s ease-in-out';
            carousel.style.transform = `translateX(${-((carouselItems.length + currentIndex) % totalItems) * itemWidth}px)`;

            carousel.addEventListener('transitionend', () => {
                isTransitioning = false;
                if (currentIndex >= carouselItems.length) {
                    carousel.style.transition = 'none';
                    currentIndex = 0;
                    carousel.style.transform = `translateX(${-itemWidth * carouselItems.length}px)`;
                } else if (currentIndex < -carouselItems.length) {
                    carousel.style.transition = 'none';
                    currentIndex = 0;
                    carousel.style.transform = `translateX(${-itemWidth * carouselItems.length}px)`;
                }
            }, { once: true });
        }
    }

    function startAutoPlay() {
        autoPlayInterval = setInterval(() => updateCarousel(1), 2000);
    }

    function stopAutoPlay() {
        clearInterval(autoPlayInterval);
    }

    // Navigation en cliquant sur les boutons
    prevButton.addEventListener('click', () => updateCarousel(-1));
    nextButton.addEventListener('click', () => updateCarousel(1));

    // Arrête l'auto-play au survol, et le redémarre lorsque la souris quitte
    carousel.addEventListener('mouseenter', stopAutoPlay);
    carousel.addEventListener('mouseleave', startAutoPlay);

    // Démarrage de l'auto-play
    startAutoPlay();
});
</script>

