<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@lang('messages.add_new_section') | {{ $clubName }}</title>
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
    <x-page-title :subtitle="__('messages.add_new_section')">
    @lang('messages.add_new_section')
</x-page-title>

        <form action="{{ route('about.store') }}" method="POST">
            @csrf
            <div class="mb-6">
                <label for="title" class="block text-sm font-medium text-gray-700" style="color: {{ $primaryColor }};">@lang('messages.title')</label>
                <input type="text" name="title" id="title" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-{{ $primaryColor }}" required>
            </div>

            <div class="mb-6">
                <label for="content" class="block text-sm font-medium text-gray-700" style="color: {{ $primaryColor }};">@lang('messages.content')</label>
                <textarea name="content" id="content" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-{{ $primaryColor }}"></textarea>
            </div>

            <div class="text-center">
                <button type="submit" 
                        class="text-white font-bold py-2 px-6 rounded-full transition duration-200 shadow-lg"
                        style="background-color: {{ $primaryColor }};"
                        onmouseover="this.style.backgroundColor='{{ $secondaryColor }}'"
                        onmouseout="this.style.backgroundColor='{{ $primaryColor }}';">
                    @lang('messages.save_section')
                </button>
            </div>
        </form>
    </div>

    <!-- Include the Footer component -->
    <x-footer />

    <script src="https://cdn.ckeditor.com/ckeditor5/34.1.0/classic/ckeditor.js"></script>
    <script>
      ClassicEditor
    .create(document.querySelector('#content'))
    .then(editor => {
        editor.model.document.on('change:data', () => {
            document.querySelector('#content').value = editor.getData();
        });
    })
    .catch(error => {
        console.error(error);
    });
    </script>

</body>

</html>
