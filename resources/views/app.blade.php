<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="theme-color" content="#0B0C10">
        <meta name="format-detection" content="telephone=no">

        {{-- Default title (pages override via Inertia <Head>) --}}
        <title inertia>{{ config('app.name', 'Dina Kenitra FC') }}</title>

        {{-- Default SEO fallbacks. Individual pages override these via the <SEO> component. --}}
        <meta name="description" content="Dina Kenitra Futsal Club. Club de futsal de la ville de Kénitra depuis 2011. Calendrier, résultats, effectif et actualités.">
        <meta name="author" content="Dina Kenitra FC">
        <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1">
        <link rel="canonical" href="{{ url()->current() }}">

        {{-- Open Graph defaults --}}
        <meta property="og:site_name" content="Dina Kenitra FC">
        <meta property="og:type" content="website">
        <meta property="og:locale" content="fr_MA">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:title" content="Dina Kenitra Futsal Club">
        <meta property="og:description" content="Club de futsal de Kénitra depuis 2011. Championnat, formation, ambitions.">
        <meta property="og:image" content="{{ url('/logo-dinakenitra.png') }}">
        <meta property="og:image:width" content="512">
        <meta property="og:image:height" content="512">
        <meta property="og:image:alt" content="Crest Dina Kenitra Futsal Club">

        {{-- Twitter Card defaults --}}
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="Dina Kenitra Futsal Club">
        <meta name="twitter:description" content="Club de futsal de Kénitra depuis 2011.">
        <meta name="twitter:image" content="{{ url('/logo-dinakenitra.png') }}">

        {{-- Favicons --}}
        <link rel="icon" type="image/png" href="/logo-dinakenitra.png">
        <link rel="apple-touch-icon" href="/logo-dinakenitra.png">

        {{-- Fonts (preconnect + preload) --}}
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Space+Grotesk:wght@500;600;700&family=Fraunces:opsz,wght,SOFT@9..144,400;9..144,500;9..144,600;9..144,700&family=JetBrains+Mono:wght@500;700&display=swap">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Space+Grotesk:wght@500;600;700&family=Fraunces:opsz,wght,SOFT@9..144,400;9..144,500;9..144,600;9..144,700&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">

        {{-- Structured data: Organization + SportsTeam (global, always present) --}}
        <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@graph": [
                {
                    "@type": "SportsOrganization",
                    "@id": "{{ url('/') }}#organization",
                    "name": "Dina Kenitra Futsal Club",
                    "alternateName": ["Dina Kenitra FC", "DKFC"],
                    "url": "{{ url('/') }}",
                    "logo": "{{ url('/logo-dinakenitra.png') }}",
                    "foundingDate": "2011",
                    "foundingLocation": {
                        "@type": "Place",
                        "name": "Kénitra, Maroc"
                    },
                    "sport": "Futsal",
                    "address": {
                        "@type": "PostalAddress",
                        "addressLocality": "Kénitra",
                        "addressCountry": "MA"
                    },
                    "email": "contact@dinakenitrafc.ma"
                },
                {
                    "@type": "SportsTeam",
                    "@id": "{{ url('/') }}#team",
                    "name": "Dina Kenitra Futsal Club",
                    "sport": "Futsal",
                    "url": "{{ url('/') }}",
                    "logo": "{{ url('/logo-dinakenitra.png') }}",
                    "memberOf": {
                        "@id": "{{ url('/') }}#organization"
                    },
                    "location": {
                        "@type": "Place",
                        "name": "Complexe Sportif Municipal, Kénitra"
                    }
                },
                {
                    "@type": "WebSite",
                    "@id": "{{ url('/') }}#website",
                    "url": "{{ url('/') }}",
                    "name": "Dina Kenitra FC",
                    "publisher": { "@id": "{{ url('/') }}#organization" },
                    "inLanguage": "fr-MA"
                }
            ]
        }
        </script>

        <script>
            (function () {
                try {
                    const stored = localStorage.getItem('theme');
                    const isDark = stored ? stored === 'dark' : true;
                    document.documentElement.classList.toggle('dark', isDark);
                } catch (e) {}
            })();
        </script>

        @routes
        @viteReactRefresh
        @vite(['resources/js/app.tsx', "resources/js/Pages/{$page['component']}.tsx"])
        @inertiaHead
    </head>
    <body class="antialiased">
        @inertia
    </body>
</html>
