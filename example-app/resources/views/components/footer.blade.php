@php
    // Ces variables sont maintenant disponibles globalement et ne dépendent plus de l'utilisateur connecté
    $primaryColor = $primaryColor ?? '#1F2937'; // Gris par défaut
    $secondaryColor = $secondaryColor ?? '#FF0000'; // Rouge par défaut
    $logoPath = $logoPath ?? null;
    $clubName = $clubName ?? 'Default Club Name';
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
</style>

<footer class="text-white py-12 mt-auto" style="background-color: {{ $primaryColor }};">
    <div class="container mx-auto px-6">
        <div class="flex flex-wrap justify-between">
            <!-- Logo and Social Media -->
            <div class="w-full md:w-1/4 mb-6 md:mb-0">
                <img src="{{ $logoPath }}" alt="Club Logo" class="mb-6" style="height: 60px; width: auto;">
                <div class="flex space-x-4">
                    <a href="#" class="text-white hover:text-gray-300">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="text-white hover:text-gray-300">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="text-white hover:text-gray-300">
                        <i class="fab fa-youtube"></i>
                    </a>
                </div>
            </div>

            <!-- Links -->
            <div class="w-full md:w-1/4 mb-6 md:mb-0">
                <h3 class="font-bold mb-4">ABOUT US</h3>
                <ul class="space-y-2">
                    <li><a href="#" class="hover:text-gray-300">News</a></li>
                    <li><a href="#" class="hover:text-gray-300">Club info</a></li>
                    <li><a href="#" class="hover:text-gray-300">Sports Hall</a></li>
                    <li><a href="#" class="hover:text-gray-300">Board</a></li>
                    <li><a href="#" class="hover:text-gray-300">Regulations</a></li>
                </ul>
            </div>

            <div class="w-full md:w-1/4 mb-6 md:mb-0">
                <h3 class="font-bold mb-4">TEAM FTA</h3>
                <ul class="space-y-2">
                    <li><a href="#" class="hover:text-gray-300">Elite</a></li>
                    <li><a href="#" class="hover:text-gray-300">Women</a></li>
                    <li><a href="#" class="hover:text-gray-300">U21</a></li>
                    <li><a href="#" class="hover:text-gray-300">Technical & Medical Staff</a></li>
                    <li><a href="#" class="hover:text-gray-300">Employees</a></li>
                    <li><a href="#" class="hover:text-gray-300">Youth Development</a></li>
                </ul>
            </div>

            <div class="w-full md:w-1/4 mb-6 md:mb-0">
                <h3 class="font-bold mb-4">MATCHES</h3>
                <ul class="space-y-2">
                    <li><a href="#" class="hover:text-gray-300">Next Matches</a></li>
                    <li><a href="#" class="hover:text-gray-300">Results</a></li>
                    <li><a href="#" class="hover:text-gray-300">General Calendar</a></li>
                </ul>
            </div>

            <!-- Contact Information -->
            <div class="w-full md:w-1/4">
                <h3 class="font-bold mb-4">CONTACT FTA</h3>
                <ul class="space-y-2">
                    <li>
                        <i class="fas fa-map-marker-alt"></i> 
                        Sportcomplex Het Rooi Berchemstadionstraat
                    </li>
                    <li>
                        <i class="fas fa-phone-alt"></i> 
                        GC: +32 488 87 34 00
                    </li>
                    <li>
                        <i class="fas fa-envelope"></i> 
                        info@ftantwerpen.be
                    </li>
                </ul>
            </div>
        </div>

        <div class="mt-8 border-t border-gray-500 pt-6 flex flex-col md:flex-row justify-between items-center text-sm text-gray-300">
            <p>&copy; 2024 {{ $clubName }} - Website by NA Innovations</p>
            <div class="mt-4 md:mt-0 space-x-4">
                <a href="#" class="hover:text-white">Privacy</a>
                <a href="#" class="hover:text-white">Terms & Conditions</a>
                <a href="#" class="hover:text-white">Cookies</a>
            </div>
        </div>
    </div>
</footer>