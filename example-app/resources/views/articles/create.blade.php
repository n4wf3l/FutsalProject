<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Article | {{ $clubName }}</title>
    @if($logoPath)
        <link rel="icon" href="{{ $logoPath }}" type="image/png"> <!-- Type de l'image selon le type du logo -->
    @endif
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @vite('resources/css/app.css')
    <style>
        .add-article-container {
            max-width: 1200px; /* Increased max-width */
            margin: 50px auto;
            padding: 2rem;
            background-color: #f9f9f9;
            border-radius: 0.5rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width:100%;
        }

        .add-article-title {
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
            margin-bottom: 0.5rem;
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
            overflow-wrap: break-word;
            white-space: pre-wrap;
        }

        .form-input:focus,
        .form-textarea:focus {
            border-color: #1D4ED8;
            outline: none;
        }

        .form-textarea {
            min-height: 200px; /* Changed initial height */
            resize: vertical;
        }

        .form-error {
            background-color: #f44336;
            color: white;
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
        }

        .save-button,
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

        .save-button {
            background-color: #1D4ED8;
            color: white;
        }

        .save-button:hover {
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
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
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

    <div class="add-article-container">
        <h1 class="add-article-title" style="color: {{ $primaryColor }};">Add New Article</h1>

        @if ($errors->any())
        <div class="form-error">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form id="article-form" action="{{ route('articles.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-grid">
                <div>
                    <div class="mb-4">
                        <label for="title" class="form-label">Title:</label>
                        <input type="text" name="title" id="title" class="form-input" value="{{ old('title') }}" required>
                    </div>

                    <div class="mb-4">
                        <label for="description" class="form-label">Description:</label>
                        <textarea name="description" id="description" class="form-textarea">{{ old('description') }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label for="image" class="form-label">Image:</label>
                        <input type="file" name="image" id="image" class="form-input">
                    </div>
                </div>
            </div>

            <div class="button-group">
                <a href="{{ route('clubinfo') }}" class="cancel-button">Cancel</a>
                <button type="submit" class="save-button">Save Article</button>
            </div>
        </form>
    </div>

    <x-footer />

    <script src="https://cdn.ckeditor.com/ckeditor5/34.1.0/classic/ckeditor.js"></script>
    <script>
        let editor;

        ClassicEditor
        .create(document.querySelector('#description'), {
            toolbar: {
                items: [
                    'bold', 'italic', '|',
                    'bulletedList', 'numberedList', '|',
                    'undo', 'redo', '|',
                    // Supprimez 'imageUpload' et 'mediaEmbed' si vous ne voulez pas ces fonctionnalités
                    'blockQuote', 'insertTable'
                ]
            },
        })
        .then(newEditor => {
            editor = newEditor;
        })
        .catch(error => {
            console.error(error);
        });

        document.getElementById('article-form').addEventListener('submit', function(event) {
            // Synchronize CKEditor data with textarea
            document.querySelector('#description').value = editor.getData();
        });
    </script>
</body>

</html>
