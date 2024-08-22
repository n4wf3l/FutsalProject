<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News | {{ $clubName }}</title>
    @if($logoPath)
        <link rel="icon" href="{{ $logoPath }}" type="image/png">
    @endif
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @vite('resources/css/app.css')
    <style>
        .main-article-container {
            display: flex;
            gap: 20px;
            margin-bottom: 40px;
        }

        .main-article {
            flex: 2;
            background-color: #ffffff;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.2s ease;
        }

        .main-article:hover {
            transform: translateY(-5px);
        }

        .main-article a {
            display: block;
            color: inherit;
            text-decoration: none;
            height: 100%;
        }

        .main-article img {
            width: 100%;
            object-fit: cover;
            height: auto;
            max-height: 500px;
        }

        .main-article-content {
            padding: 20px;
        }

        .recent-articles {
            flex: 1;
            background-color: #ffffff;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            padding: 20px;
            position: relative;
        }

        .recent-articles h3 {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 15px;
            border-bottom: 3px solid {{ $secondaryColor }};
            padding-bottom: 5px;
        }

        .recent-articles a {
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: {{ $secondaryColor }};
            margin-bottom: 10px;
            text-decoration: none;
            padding-bottom: 5px;
        }

        .recent-articles a:hover {
            text-decoration: underline;
        }

        .recent-articles hr {
            border: none;
            border-top: 1px solid #e2e8f0; /* Tailwind CSS gray-200 */
            margin: 10px 0;
        }

        .recent-article-date {
            font-size: 0.875rem;
            color: #4a5568; /* Tailwind CSS gray-700 */
            white-space: nowrap;
        }

        .recent-articles-container {
            margin-top: 40px;
        }

        .article-item {
            display: flex;
            align-items: center;
            background-color: #ffffff;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            padding: 20px;
            transition: transform 0.2s ease, border-color 0.2s ease;
            border: 1px solid transparent;
            text-decoration: none;
            color: inherit;
        }

        .article-item:hover {
            transform: translateY(-5px);
            border-color: {{ $primaryColor }};
        }

        .article-item img {
            width: 150px;
            height: 100px;
            object-fit: cover;
            border-radius: 5px;
            margin-right: 20px;
        }

        .article-item-content {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .article-item-title {
            font-size: 1.25rem;
            font-weight: bold;
            margin-bottom: 10px;
            color: {{ $primaryColor }};
            text-decoration: none;
        }

        .article-item-title:hover {
            text-decoration: underline;
        }

        .article-item-meta {
            color: #4a5568; /* Tailwind CSS gray-700 */
            font-size: 0.875rem;
            margin-bottom: 10px;
        }

        .article-item-description {
            font-size: 1rem;
            color: #333;
            line-height: 1.5;
        }
    </style>
</head>
<body class="bg-gray-100">
    <x-navbar />

    <header class="text-center my-12" style="margin-top: 20px; margin-bottom: 20px;">
    <x-page-title subtitle="🔔 Stay informed with all the breaking stories and headlines you need to know!">
    Recent News
</x-page-title>
        @auth
        <x-button 
    route="{{ route('articles.create') }}"
    buttonText="Add Article" 
    primaryColor="#B91C1C" 
    secondaryColor="#DC2626" 
/>
        @endauth
    </header>

    <div class="flex justify-center mb-6">
        <form action="{{ route('clubinfo') }}" method="GET" class="w-full max-w-md">
            <div class="flex items-center border-b border-b-2 border-gray-500 py-2">
                <input
                    type="text"
                    name="search"
                    class="appearance-none bg-transparent border-none w-full text-gray-700 mr-3 py-1 px-2 leading-tight focus:outline-none"
                    placeholder="Search articles..."
                    aria-label="Search"
                >
                <button
                    type="submit"
                    class="text-white font-bold py-2 px-6 rounded-full transition duration-200 shadow-lg text-center"
                    style=" background-color: {{ $primaryColor }};"
                    onmouseover="this.style.backgroundColor='{{ $secondaryColor }}'"
                    onmouseout="this.style.backgroundColor='{{ $primaryColor }}'"
                >
                    Search
                </button>
            </div>
        </form>
    </div>

    <div class="container mx-auto py-12">
        @if(session('success'))
        <div class="bg-green-500 text-green p-4 rounded mb-6">
            {{ session('success') }}
        </div>
        @endif

        <div class="main-article-container">
            <!-- Main Article -->
            <div class="main-article">
                <a href="{{ route('articles.show', $articles->first()->slug) }}">
                    @if($articles->first()->image)
                    <img src="{{ asset('storage/' . $articles->first()->image) }}" alt="{{ $articles->first()->title }}">
                    @endif
                    <div class="main-article-content">
                        <h2 class="text-3xl font-bold mb-2 article-title" style="color: {{ $primaryColor }};">
                            <strong>{{ $articles->first()->title }}</strong>
                        </h2>
                        <p class="text-gray-600 mb-4">
                            {!! \Illuminate\Support\Str::limit(strip_tags($articles->first()->description, '<b><i><strong><em><ul><li><ol>'), 200) !!}
                        </p>
                        <p class="text-sm text-gray-500">Published on: {{ $articles->first()->created_at->format('d M Y, H:i') }} by
                            {{ $articles->first()->user->name }}</p>
                    </div>
                </a>
            </div>

            <!-- Recent Articles List -->
            <div class="recent-articles" data-aos="zoom-in"'>
                <h3>📰 Recent Articles</h3>
                @foreach($articles->skip(1) as $article)
                <a href="{{ route('articles.show', $article->slug) }}">
                    <span>{{ $article->title }}</span>
                    <span class="recent-article-date">{{ $article->created_at->format('d/m') }}</span>
                </a>
                <hr>
                @endforeach
            </div>
        </div>

        <!-- Recent Articles Below the Main Article -->
        <div class="recent-articles-container">
            @foreach($articles->skip(1) as $article)
            <a href="{{ route('articles.show', $article->slug) }}" class="article-item">
                @if($article->image)
                <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->title }}">
                @endif
                <div class="article-item-content">
                    <h2 class="article-item-title">{{ $article->title }}</h2>
                    <div class="article-item-meta">
                        <span>{{ $article->created_at->format('d/m') }} - </span>
                        <span>
                            {{ \Illuminate\Support\Str::limit(strip_tags($article->description), 100) }}
                        </span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>

    <x-footer />
</body>
</html>
