<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pressRelease->title }} | {{ $clubName }}</title>
    @if($logoPath)
    <link rel="icon" href="{{ $logoPath }}" type="image/png">
    @endif
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @vite('resources/css/app.css')

    <meta property="og:title" content="{{ $pressRelease->title }}" />
<meta property="og:description" content="{{ Str::limit(strip_tags($pressRelease->content), 150) }}" />
<meta property="og:image" content="{{ asset('storage/' . $pressRelease->image) }}" />
<meta property="og:url" content="{{ url()->current() }}" />
<meta property="og:site_name" content="{{ $clubName }}" />
<meta property="og:type" content="article" />
<meta property="article:published_time" content="{{ $pressRelease->created_at->toIso8601String() }}" />
<meta property="article:author" content="{{ $pressRelease->author }}" />
<meta name="DC.title" content="{{ $pressRelease->title }}" />
<meta name="DC.creator" content="{{ $pressRelease->author }}" />
<meta name="DC.date" content="{{ $pressRelease->created_at->format('Y-m-d') }}" />
<meta name="DC.language" content="en" />
<meta name="DC.publisher" content="{{ $clubName }}" />
<meta name="DC.format" content="text/html" />
<meta name="DC.identifier" content="{{ url()->current() }}" />
    <style>
        /* Conteneur principal de l'article */
        .article-container {
            max-width: 1200px;
            margin: 50px auto;
            display: flex;
            gap: 20px;
            padding: 0 20px;
            flex-direction: column;
        }

        /* Section principale de l'article */
        .main-article-section {
            flex: 2;
            padding: 2rem;
            background-color: #ffffff;
            border-radius: 0.5rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Section des articles récents */
        .recent-articles-section {
            flex: 1;
            padding: 2rem;
            background-color: #ffffff;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-height: fit-content;
        }

        /* Titre de l'article */
        .article-title {
            font-size: 2.5rem;
            font-weight: bold;
            color: {{ $primaryColor }};
            text-align: center;
            margin-bottom: 1.5rem;
        }

        @media (max-width: 767px) {
            .article-title {
                font-size: 1.75rem; /* Réduit la taille du titre pour les écrans plus petits */
            }
        }

        /* Description de l'article */
        .article-description {
            font-size: 1.2rem;
            line-height: 1.6;
            color: #333;
            margin-bottom: 1.5rem;
        }

        /* Métadonnées de l'article */
        .article-meta {
            font-size: 0.9rem;
            color: #888;
            text-align: center;
            margin-bottom: 2rem;
        }

        /* Image de l'article */
        .article-image {
            width: 100%;
            height: auto;
            border-radius: 0.5rem;
            display: block;
            margin: 0 auto 1.5rem auto;
        }

        /* Titre des articles récents */
        .recent-articles-section h3 {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 15px;
            border-bottom: 3px solid {{ $secondaryColor }};
            padding-bottom: 5px;
        }

        /* Liens des articles récents */
        .recent-articles-section a {
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: {{ $secondaryColor }};
            margin-bottom: 10px;
            text-decoration: none;
            padding-bottom: 5px;
        }

        /* Surlignage des liens d'articles récents */
        .recent-articles-section a:hover {
            text-decoration: underline;
        }

        /* Ligne de séparation des articles récents */
        .recent-articles-section hr {
            border: none;
            border-top: 1px solid #e2e8f0;
            margin: 10px 0;
        }

        /* Date des articles récents */
        .recent-article-date {
            font-size: 0.875rem;
            color: #4a5568;
            white-space: nowrap;
        }

        /* Style responsive */
        @media (min-width: 768px) {
            .article-container {
                flex-direction: row;
            }

            .main-article-section {
                flex: 2;
            }

            .recent-articles-section {
                flex: 1;
            }

            .article-image {
                max-width: 50%; /* Limite la taille de l'image sur les grands écrans */
                margin: 0 auto 1.5rem auto;
            }
        }
    </style>
</head>

<body class="bg-gray-100">
    <x-navbar />

    <div class="article-container">
    <!-- Section principale de l'article -->
    <div class="main-article-section">
        <div class="arrow-line-container" style="display: flex; align-items: center;">
            <div class="returnIcon" style="margin-right: 10px; margin-bottom: 20px; transition: transform 0.3s ease;">
                <a href="{{ route('press_releases.index') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6" style="color: {{ $primaryColor }};">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
            </div>
            <p class="newsText" style="background-color: {{ $primaryColor }};
                color: #ffffff;
                font-size: 0.875rem;
                margin-bottom: 1rem;
                text-transform: uppercase;
                font-weight: bold;
                padding: 4px 8px;
                border-radius: 4px;
                margin-left: auto;">
                @lang('messages.press_release')
            </p>
        </div>

        <h1 class="article-title">{{ $pressRelease->title }}</h1>
        @if($pressRelease->image)
        <img src="{{ asset('storage/' . $pressRelease->image) }}" alt="{{ $pressRelease->title }}" class="article-image">
        @endif
        <p class="article-meta" style="font-weight: bold;">
            {{ __('messages.published_on') }} {{ \Carbon\Carbon::parse($pressRelease->created_at)->format('d M Y, H:i') }}
        </p>
        <p class="article-description">{!! nl2br(e($pressRelease->content)) !!}</p>
    </div>

        <!-- Section des articles récents -->
        <div class="recent-articles-section">
            <h3>📰 {{ __('messages.recent_articles') }}</h3>
            @foreach($recentArticles as $recentArticle)
            <a href="{{ route('articles.show', $recentArticle->slug) }}">
                <span>{{ $recentArticle->title }}</span>
                <span class="recent-article-date">{{ $recentArticle->created_at->format('d/m') }}</span>
            </a>
            <hr>
            @endforeach
        </div>
    </div>

    <x-footer />

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var oembeds = document.querySelectorAll('oembed[url]');
            oembeds.forEach(function (oembed) {
                var iframe = document.createElement('iframe');

                // Set the iframe attributes to match the oEmbed's URL
                iframe.setAttribute('width', '100%');
                iframe.setAttribute('height', '315');
                iframe.setAttribute('src', oembed.getAttribute('url').replace('watch?v=', 'embed/'));
                iframe.setAttribute('frameborder', '0');
                iframe.setAttribute('allowfullscreen', 'true');

                // Replace the <oembed> element with the new iframe
                oembed.parentNode.replaceChild(iframe, oembed);
            });
        });
    </script>
</body>

</html>
