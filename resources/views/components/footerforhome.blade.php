@php
    if (isset($_COOKIE['locale'])) {
        app()->setLocale($_COOKIE['locale']);
    }
@endphp

@php
    use Illuminate\Support\Facades\Route;
    use App\Models\ClubInfo;

    $clubInfo = ClubInfo::first();
    $federationLogo = $clubInfo && $clubInfo->federation_logo ? asset('storage/' . $clubInfo->federation_logo) : asset('unknown.png');
    $organizationLogo = $clubInfo && $clubInfo->organization_logo ? asset('storage/' . $clubInfo->organization_logo) : null;
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
</style>
<footer class="text-white py-12 mt-auto" style="background-color: {{ $primaryColor }};">
    <div class="footer-center container mx-auto px-6">
        <div class="flex flex-wrap justify-between">
            <div class="w-full md:w-1/4 mb-6 md:mb-0 footer-logo">
                <div class="flex items-center space-x-4 mb-6">
                    <!-- Affiche le logo du club uniquement s'il est disponible -->
                    @if (!empty($logoPath))
                        <img src="{{ asset($logoPath) }}" 
                             alt="@lang('messages.logo_not_available')" 
                             style="height: 60px; width: auto;">
                    @endif

                    <!-- Affiche le logo de la fédération uniquement s'il est disponible -->
                    @if (!empty($federationLogo))
                        <img src="{{ asset($federationLogo) }}" 
                             alt="@lang('messages.logo_not_available')" 
                             style="height: 60px; width: auto;">
                    @endif

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

