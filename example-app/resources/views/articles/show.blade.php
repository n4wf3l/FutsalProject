<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $article->title }}</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @vite('resources/css/app.css')
    <style>
        .article-container {
            max-width: 1200px; /* Increased width */
            margin: 50px auto;
            padding: 2rem;
            background-color: #ffffff;
            border-radius: 0.5rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .article-title {
            font-size: 2.5rem;
            font-weight: bold;
            color: #1D4ED8;
            text-align: center;
            margin: 1.5rem 0;
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
            max-width: 100%;
            height: auto;
            border-radius: 0.5rem;
            display: block;
            margin: 0 auto 1.5rem auto; /* Margin added to the bottom of the image */
        }
    </style>
</head>

<body class="bg-gray-100">
    <x-navbar />

    <div class="article-container">
        @if($article->image)
        <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->title }}" class="article-image">
        @endif
        <h1 class="article-title" style="color: {{ $primaryColor }};">{{ $article->title }}</h1>
                <p class="article-meta">Published on: {{ $article->created_at->format('d M Y, H:i') }} by {{ $article->user->name }}</p>
        <p class="article-description">{!! $article->description !!}</p>
    </div>

    <x-footer />
</body>

</html>
