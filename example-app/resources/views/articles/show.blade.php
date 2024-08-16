<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $article->title }}</title>
    @if($logoPath)
        <link rel="icon" href="{{ $logoPath }}" type="image/png"> <!-- Type de l'image selon le type du logo -->
    @endif
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @vite('resources/css/app.css')
    <style>
        .article-container {
            max-width: 1200px;
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

        /* Styles for the arrow inside a circle at the start of the line */
        .arrow-line-container {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            position: relative;
        }

        .arrow-circle {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 30px;
            height: 30px;
            border: 2px solid {{ $primaryColor }};
            border-radius: 50%;
            background-color: white;
            position: absolute;
            top: -15px;
            left: -15px;
            z-index: 1;
            transition: all 0.3s ease;
        }

        .arrow-circle:hover {
            background-color: {{ $primaryColor }};
            width: 35px;
            height: 35px;
            cursor: pointer;
        }

        .arrow-circle a {
            color: gray;
            font-size: 16px;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .arrow-circle:hover a {
            color: white;
        }

        hr {
            flex-grow: 1;
            border: none;
            border-top: 2px solid {{ $primaryColor }};
            margin-left: 20px;
        }
    </style>
</head>

<body class="bg-gray-100">
    <x-navbar />

    <div class="article-container">
    <!-- Arrow and Line -->
    <div class="arrow-line-container">
        <div class="arrow-circle">
            <a href="{{ route('clubinfo') }}">&larr;</a>
        </div>
        <hr>
    </div>
        @if($article->image)
        <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->title }}" class="article-image">
        @endif
        <h1 class="article-title" style="color: {{ $primaryColor }};">{{ $article->title }}</h1>
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

    <x-footer />
</body>

</html>
