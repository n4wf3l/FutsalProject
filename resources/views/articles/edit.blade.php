<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@lang('messages.edit_article') | {{ $clubName }}</title>
    @if($logoPath)
        <link rel="icon" href="{{ $logoPath }}" type="image/png"> <!-- Type de l'image selon le type du logo -->
    @endif
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @vite('resources/css/app.css')
    <style>
        .edit-article-container {
            max-width: 900px;
            margin: 50px auto;
            padding: 2rem;
            background-color: #f9f9f9;
            border-radius: 0.5rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .edit-article-title {
            font-size: 2.5rem;
            font-weight: bold;
            text-align: center;
            margin-bottom: 1.5rem;
            color: #1D4ED8;
        }

        .form-label {
            font-weight: 500;
            color: #333;
            font-size: 1.2rem;
        }

        .form-input,
        .form-textarea {
            width: 100%;
            padding: 1rem;
            border-radius: 0.5rem;
            border: 1px solid #ccc;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1) inset;
            font-size: 1.1rem;
            transition: border-color 0.3s ease;
        }

        .form-input:focus,
        .form-textarea:focus {
            border-color: #1D4ED8;
            outline: none;
        }

        .form-textarea {
            height: 300px;
            resize: vertical;
        }

        .form-error {
            background-color: #f44336;
            color: white;
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
        }

        .update-button,
        .cancel-button {
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            text-align: center;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
            width: 100%;
            max-width: 200px;
        }

        .update-button {
            background-color: #1D4ED8;
            color: white;
        }

        .update-button:hover {
            background-color: #2563EB;
        }

        .cancel-button {
            background-color: #f44336;
            color: white;
        }

        .cancel-button:hover {
            background-color: #e53935;
        }

        .form-image-preview {
            max-height: 200px;
            margin-bottom: 1rem;
            border-radius: 0.5rem;
            object-fit: cover;
            width: 100%;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            align-items: center;
        }

        .form-grid > div {
            display: flex;
            flex-direction: column;
        }

        .button-group {
            display: flex;
            justify-content: center;
            gap: 16px;
            margin-top: 2rem;
        }
    </style>
</head>

<body class="bg-gray-100">
    <x-navbar />

    <div class="edit-article-container">
        <x-page-title :subtitle="__('messages.edit_article')">
            @lang('messages.edit_article')
        </x-page-title>

        @if ($errors->any())
        <div class="form-error">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('articles.update', $article->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-grid">
                <div>
                    <div class="mb-4">
                        <label for="title" class="form-label">@lang('messages.title'):</label>
                        <input type="text" name="title" id="title" class="form-input" value="{{ old('title', $article->title) }}" required>
                    </div>

                    <div class="mb-4">
                        <label for="content" class="form-label">@lang('messages.content'):</label>
                        <textarea name="content" id="content" class="form-textarea" required>{{ old('content', $article->content) }}</textarea>
                    </div>
                </div>

                <div>
                    @if($article->image)
                    <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->title }}" class="form-image-preview">
                    @endif
                    <div class="mb-4">
                        <label for="image" class="form-label">@lang('messages.choose_new_image'):</label>
                        <input type="file" name="image" id="image" class="form-input">
                    </div>
                </div>
            </div>

            <div class="button-group">
                <a href="{{ route('clubinfo') }}" class="cancel-button">@lang('messages.cancel')</a>
                <button type="submit" class="update-button">@lang('messages.update')</button>
            </div>
        </form>
    </div>

    <x-footer />

    <script src="https://cdn.ckeditor.com/ckeditor5/34.1.0/classic/ckeditor.js"></script>
    <script>
        ClassicEditor
        .create(document.querySelector('#content'), {
            toolbar: {
                items: [
                    'bold', 'italic', '|',
                    'fontColor', 'fontBackgroundColor', '|',
                    'bulletedList', 'numberedList', '|',
                    'blockQuote', 'insertTable', '|',
                    'undo', 'redo'
                ]
            }
        })
        .catch(error => {
            console.error(error);
        });
    </script>
</body>

</html>
