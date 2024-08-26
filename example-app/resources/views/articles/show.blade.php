<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $article->title }}</title>
    @if($logoPath)
        <link rel="icon" href="{{ $logoPath }}" type="image/png">
    @endif
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @vite('resources/css/app.css')
    <style>
        .article-container {
            max-width: 1200px;
            margin: 50px auto;
            display: flex;
            flex-direction: column;
            gap: 20px;
            padding: 0 20px;
        }

        .main-article-section {
            flex: 2;
            padding: 2rem;
            background-color: #ffffff;
            border-radius: 0.5rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .recent-articles-section {
            flex: 1;
            padding: 2rem;
            background-color: #ffffff;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-height: fit-content;
        }

        .article-title {
            font-size: 2.5rem;
            font-weight: bold;
            color: {{ $primaryColor }};
            text-align: center;
            margin-bottom: 1.5rem;
        }

        @media (max-width: 767px) {
            .article-title {
                font-size: 1.75rem; /* Réduire la taille du titre sur les petits écrans */
            }
        }

        .article-description {
            font-size: 1.2rem;
            line-height: 1.6;
            color: #333;
            margin-bottom: 1.5rem;
        }

        .article-meta {
            font-size: 0.9rem;
            color: #888;
            text-align: center;
            margin-bottom: 2rem;
        }

        .article-image {
            width: 100%; /* Assurez-vous que l'image prend toute la largeur */
            height: auto;
            border-radius: 0.5rem;
            display: block;
            margin: 0 auto 1.5rem auto;
        }

        .edit-btn {
            background-color: #1D4ED8;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-align: center;
            display: inline-block;
            transition: background-color 0.2s;
            margin-right: 10px;
        }

        .edit-btn:hover {
            background-color: #135ba1;
        }

        .delete-btn {
            background-color: #e3342f;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-align: center;
            display: inline-block;
            transition: background-color 0.2s;
        }

        .delete-btn:hover {
            background-color: #cc1f1a;
        }

        .button-container {
            text-align: center;
            margin-top: 20px;
        }

        .recent-articles-section h3 {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 15px;
            border-bottom: 3px solid {{ $secondaryColor }};
            padding-bottom: 5px;
        }

        .recent-articles-section a {
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: {{ $secondaryColor }};
            margin-bottom: 10px;
            text-decoration: none;
            padding-bottom: 5px;
        }

        .recent-articles-section a:hover {
            text-decoration: underline;
        }

        .recent-articles-section hr {
            border: none;
            border-top: 1px solid #e2e8f0;
            margin: 10px 0;
        }

        .recent-article-date {
            font-size: 0.875rem;
            color: #4a5568;
            white-space: nowrap;
        }

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
        <!-- Main Article Section -->
        <div class="main-article-section">
            <div class="arrow-line-container">
                <div class="arrow-circle">
                    <a href="{{ route('clubinfo') }}">&larr;</a>
                </div>
                <hr>
            </div>
            <h1 class="article-title">{{ $article->title }}</h1>
            @if($article->image)
            <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->title }}" class="article-image">
            @endif
            <p class="article-meta">Published on: {{ $article->created_at->format('d M Y, H:i') }} by {{ $article->user->name }}</p>
            <p class="article-description">{!! $article->description !!}</p>

            @auth
            <div class="button-container">
                <a href="{{ route('articles.edit', $article->id) }}" class="edit-btn">Edit</a>
                <form action="{{ route('articles.destroy', $article->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this article?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="delete-btn">Delete</button>
                </form>
            </div>
            @endauth
        </div>

        <!-- Recent Articles Section -->
        <div class="recent-articles-section">
            <h3>📰 Recent Articles</h3>
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
        document.addEventListener("DOMContentLoaded", function() {
            var oembeds = document.querySelectorAll('oembed[url]');
            oembeds.forEach(function(oembed) {
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
