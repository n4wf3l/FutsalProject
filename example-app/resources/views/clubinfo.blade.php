<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clubinfo</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @vite('resources/css/app.css')
    <style>
        .article-card {
            background-color: #ffffff;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease-in-out;
            overflow: hidden;
            margin: 30px; /* Add margin around each card */
            width: 100%;
            max-width: 400px; /* Set a maximum width for the card */
        }
        
        .article-card:hover {
            transform: translateY(-5px);
        }

        .article-image {
            height: 200px;
            object-fit: cover;
            width: 100%; /* Ensure the image takes the full width of the card */
        }

        .read-more-btn {
            color: #1D4ED8;
            font-weight: bold;
            text-decoration: none;
        }

        .read-more-btn:hover {
            text-decoration: underline;
        }

        /* Adjust grid container */
        .articles-container {
            display: grid;
            grid-template-columns: repeat(1, 1fr);
        }

        @media(min-width: 768px) {
            .articles-container {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media(min-width: 1024px) {
            .articles-container {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        /* Custom styles for the title and buttons */
        .article-title {
            color: var(--primary-color, #1D4ED8); /* Replace with your primary color */
            padding-bottom: 10px;
        }

        .edit-btn {
            background-color: #1D4ED8;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            transition: background-color 0.2s;
        }

        .edit-btn:hover {
            background-color: #135ba1;
        }

        .delete-btn {
            background-color: #e3342f;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            transition: background-color 0.2s;
        }

        .delete-btn:hover {
            background-color: #cc1f1a;
        }

    </style>
</head>
<body class="bg-gray-100">
    <x-navbar />

    <header class="text-center my-12" style="margin-top: 20px;">
        <h1 class="text-6xl font-bold text-gray-900" style="font-size:60px;">News</h1>
        <div class="flex justify-center items-center mt-4">
            <p class="text-xl text-gray-600" style="margin-bottom: 20px;">Discover additional information by hovering with your mouse.</p>
        </div>
        @auth
        <a href="{{ route('articles.create') }}"
            class="text-white font-bold py-2 px-6 rounded-full transition duration-200 shadow-lg text-center"
                   style=" background-color: {{ $primaryColor }};"
                   onmouseover="this.style.backgroundColor='{{ $secondaryColor }}'"
                   onmouseout="this.style.backgroundColor='{{ $primaryColor }}'">Add
                Article</a>
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

        <div class="articles-container">
            @foreach($articles as $article)
            <div class="article-card">
                @if($article->image)
                <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->title }}" class="article-image">
                @endif
                <div class="p-6"> <!-- Increased padding -->
                    <h2 class="text-xl font-bold mb-2 article-title" style="color: {{ $primaryColor }};"
                   ><strong>{{ $article->title }}</strong></h2>
                   <p class="text-gray-600 mb-4">
                {!! \Illuminate\Support\Str::limit(strip_tags($article->description, '<b><i><strong><em><ul><li><ol>'), 100) !!}
            </p>
                    <a href="{{ route('articles.show', $article->id) }}" class="read-more-btn mb-4 block" style="color: {{ $secondaryColor }};"
                   onmouseover="this.style.color='{{ $primaryColor }}'"
                   onmouseout="this.style.color='{{ $secondaryColor }}'">More →</a>
                    <p class="text-sm text-gray-500">Published on: {{ $article->created_at->format('d M Y, H:i') }} by
                        {{ $article->user->name }}</p>
                    @auth
                    <div class="flex mt-4" style="justify-content: center; gap: 16px;">
    <a href="{{ route('articles.edit', $article->id) }}" class="edit-btn">Edit</a>
    <form action="{{ route('articles.destroy', $article->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="delete-btn">Delete</button>
    </form>
</div>
                    @endauth
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <x-footer />
</body>
</html>
