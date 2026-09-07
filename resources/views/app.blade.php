@php
    $locale = app()->getLocale();
    $direction = in_array($locale, ['ar']) ? 'rtl' : 'ltr';
    $ogLocaleMap = ['fr' => 'fr_MA', 'en' => 'en_US', 'ar' => 'ar_MA'];
    $ogLocale = $ogLocaleMap[$locale] ?? 'fr_MA';
    $ogLocaleAlternates = array_values(array_diff($ogLocaleMap, [$ogLocale]));

    $meta = $seoMeta ?? [];
    $metaTitle = $meta['title'] ?? 'Dina Kenitra Futsal Club';
    $metaDescription = $meta['description'] ?? 'Club de futsal de Kénitra depuis 2011. Championnat, formation, ambitions.';
    $metaImage = $meta['image'] ?? url('/logo-dinakenitra.png');
    $metaType = $meta['type'] ?? 'website';
    $metaUrl = $meta['url'] ?? url()->current();
    $isCustomImage = isset($meta['image']) && $meta['image'];
@endphp
<!DOCTYPE html>
<html lang="{{ $locale }}" dir="{{ $direction }}" data-locale="{{ $locale }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="theme-color" content="#0B0C10">
        <meta name="format-detection" content="telephone=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        {{-- Default title (pages override via Inertia <Head>) --}}
        <title inertia>{{ $metaTitle }}</title>

        {{-- SEO tags. Server-rendered so social crawlers see per-page meta without needing SSR. --}}
        <meta name="description" content="{{ $metaDescription }}">
        <meta name="author" content="Dina Kenitra FC">
        <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1">
        <link rel="canonical" href="{{ $metaUrl }}">

        {{-- Open Graph --}}
        <meta property="og:site_name" content="Dina Kenitra FC">
        <meta property="og:type" content="{{ $metaType }}">
        <meta property="og:locale" content="{{ $ogLocale }}">
        @foreach ($ogLocaleAlternates as $altLocale)
            <meta property="og:locale:alternate" content="{{ $altLocale }}">
        @endforeach
        <meta property="og:url" content="{{ $metaUrl }}">
        <meta property="og:title" content="{{ $metaTitle }}">
        <meta property="og:description" content="{{ $metaDescription }}">
        <meta property="og:image" content="{{ $metaImage }}">
        @unless ($isCustomImage)
            <meta property="og:image:width" content="512">
            <meta property="og:image:height" content="512">
        @endunless
        <meta property="og:image:alt" content="{{ $metaTitle }}">

        {{-- Twitter Card --}}
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="{{ $metaTitle }}">
        <meta name="twitter:description" content="{{ $metaDescription }}">
        <meta name="twitter:image" content="{{ $metaImage }}">

        {{-- Favicons --}}
        <link rel="icon" type="image/png" href="/logo-dinakenitra.png">
        <link rel="apple-touch-icon" href="/logo-dinakenitra.png">

        {{-- Fonts (preconnect + preload) --}}
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Space+Grotesk:wght@500;600;700&family=Fraunces:ital,opsz,wght@0,9..144,400..700;1,9..144,400..700&family=JetBrains+Mono:wght@500;700&display=swap">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Space+Grotesk:wght@500;600;700&family=Fraunces:ital,opsz,wght@0,9..144,400..700;1,9..144,400..700&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">

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
