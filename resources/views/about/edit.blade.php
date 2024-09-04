<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@lang('messages.edit_section') | {{ $clubName }}</title>
    @if($logoPath)
        <link rel="icon" href="{{ $logoPath }}" type="image/png"> <!-- Type de l'image selon le type du logo -->
    @endif
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-100">

    <!-- Include the Navbar component -->
    <x-navbar />

    <div class="container mx-auto py-12">
        <x-page-title :subtitle="__('messages.edit_section')">
            @lang('messages.edit_section')
        </x-page-title>

        <form action="{{ route('about.update', $aboutSection->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-6">
                <label for="title" class="block text-sm font-medium text-gray-700" style="color: {{ $primaryColor }};">@lang('messages.title')</label>
                <input type="text" name="title" id="title" value="{{ $aboutSection->title }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-{{ $primaryColor }}" required>
            </div>

            <div class="mb-6">
                <label for="content" class="block text-sm font-medium text-gray-700" style="color: {{ $primaryColor }};">@lang('messages.content')</label>
                <textarea name="content" id="content" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-{{ $primaryColor }}" required>{!! $aboutSection->content !!}</textarea>
            </div>

            <div class="text-center">
                <button type="submit" 
                        class="text-white font-bold py-2 px-6 rounded-full transition duration-200 shadow-lg"
                        style="background-color: {{ $primaryColor }};"
                        onmouseover="this.style.backgroundColor='{{ $secondaryColor }}'"
                        onmouseout="this.style.backgroundColor='{{ $primaryColor }}';">
                    @lang('messages.update_section')
                </button>
            </div>
        </form>
    </div>

    <!-- Include the Footer component -->
    <x-footer />

    <script src="https://cdn.ckeditor.com/ckeditor5/34.1.0/classic/ckeditor.js"></script>
    <script>
    ClassicEditor
        .create(document.querySelector('#content'), {
            toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote'],
            heading: {
                options: [
                    { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                    { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                    { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                    { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' }
                ]
            }
        })
        .catch(error => {
            console.error(error);
        });
    </script>

</body>

</html>
