<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.press_releases') }} | {{ $clubName }}</title>
    @if($logoPath)
        <link rel="icon" href="{{ $logoPath }}" type="image/png">
    @endif
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    <style>
        /* Styles pour les cartes de communiqués de presse */
        .press-release-card {
            background-color: #ffffff;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.2s ease-in-out;
            margin-bottom: 24px;
            position: relative;
        }

        .press-release-card:hover {
            transform: translateY(-5px);
        }

        .press-release-image img {
            width: 100%;
            height: 250px;
            object-fit: cover;
        }

        .press-release-content {
            padding: 1rem;
        }

        .press-release-title {
            font-size: 1.25rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
        }

        .edit-button-emoji {
            margin-left: 8px;
            font-size: 1.25rem;
            cursor: pointer;
            border: none;
            background: none;
            color: #fbbf24;
        }

        .press-release-date {
            color: #555;
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
        }

        .press-release-category {
            background-color: {{ $primaryColor }};
            color: #ffffff;
            font-size: 0.875rem;
            margin-bottom: 1rem;
            text-transform: uppercase;
            font-weight: bold;
            padding: 4px 8px;
            display: inline-block;
            border-radius: 4px;
        }

        .press-release-view {
            color: #1D4ED8;
            font-weight: bold;
            text-decoration: none;
            display: inline-block;
        }

        .press-release-view:hover {
            text-decoration: underline;
        }

        /* Grille pour les communiqués de presse */
        .press-release-container {
            display: grid;
            grid-template-columns: 1fr; /* Une colonne par défaut */
            gap: 32px;
            padding: 32px;
        }

        /* Adaptation pour les écrans moyens */
        @media (min-width: 768px) {
            .press-release-container {
                grid-template-columns: repeat(2, 1fr); /* Deux colonnes pour les écrans moyens */
            }
        }

        /* Style de la pagination */
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .pagination a {
            color: {{ $primaryColor }};
            padding: 8px 16px;
            text-decoration: none;
            margin: 0 4px;
            border: 1px solid {{ $primaryColor }};
            border-radius: 4px;
        }

        .pagination a:hover {
            background-color: {{ $primaryColor }};
            color: #ffffff;
        }

        .pagination .active {
            background-color: {{ $primaryColor }};
            color: #ffffff;
            border: 1px solid {{ $primaryColor }};
        }

        .delete-button-x {
            position: absolute;
            top: 8px;
            right: 8px;
            background-color: rgba(220, 38, 38, 0.8);
            color: #fff;
            border: none;
            border-radius: 50%;
            padding: 4px 8px;
            cursor: pointer;
        }

        /* Style for the enlarged textarea */
        #editContent, #createContent {
            min-height: 200px;
            height: auto;
            resize: vertical;
            padding: 12px;
            font-size: 1rem;
        }

        .create-button {
            background-color: {{ $primaryColor }};
            color: #fff;
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .create-button:hover {
            background-color: {{ $secondaryColor }};
        }
    </style>
</head>
<body class="bg-gray-100" @if($backgroundImage) style="background: url('{{ asset('storage/' . $backgroundImage->image_path) }}') no-repeat center center fixed; background-size: cover;" @endif>
    <x-navbar />

    <header class="text-center my-12">
        <x-page-title :subtitle="__('messages.stay_informed')">
            {{ __('messages.press_releases') }}
        </x-page-title>
    </header>

    <main class="container mx-auto px-4">
        @auth
            <div class="flex justify-center mb-6">
                <button onclick="openModal('createPressReleaseModal')" class="create-button text-white font-bold py-2 px-6 rounded-full transition duration-200 shadow-lg">
                    {{ __('messages.create_press_release') }}
                </button>
            </div>
        @endauth

        <div class="press-release-container" data-aos="fade-up-right">
            @if($pressReleases->isEmpty())
                <div class="no-press-releases-message text-center">
                    {{ __('messages.no_press_releases') }}
                </div>
            @else
                @foreach($pressReleases as $pressRelease)
                    <div class="press-release-card">
                        @if($pressRelease->image)
                            <div class="press-release-image">
                                <a href="{{ route('press_releases.show', $pressRelease->slug) }}">
                                    <img src="{{ asset('storage/' . $pressRelease->image) }}" alt="{{ $pressRelease->title }}">
                                </a>
                                @auth
                                <form action="{{ route('press_releases.destroy', $pressRelease->id) }}" method="POST" onsubmit="return confirm('{{ __('messages.delete_confirmation') }}');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="delete-button-x">X</button>
                                </form>
                                @endauth
                            </div>
                        @endif
                        <div class="press-release-content">
                            <div class="press-release-category">{{ __('messages.press_release') }}</div>
                            <h2 class="press-release-title">
                                {{ $pressRelease->title }}
                                @auth
                                <button onclick="openEditModal('{{ $pressRelease->id }}', '{{ addslashes($pressRelease->title) }}', `{{ addslashes($pressRelease->content) }}`)" class="edit-button-emoji">🛠️</button>
                                @endauth
                            </h2>
                            <div class="press-release-date">{{ \Carbon\Carbon::parse($pressRelease->created_at)->format('l j F Y') }}</div>
                            <a href="{{ route('press_releases.show', $pressRelease->slug) }}" class="press-release-view">{{ __('messages.read_more') }} →</a>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        <!-- Pagination -->
        <div class="pagination mb-20">
            {{ $pressReleases->links('vendor.pagination.simple') }}
        </div>
    </main>

    <x-footer />

    <!-- Create Press Release Modal -->
    <div id="createPressReleaseModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6 mx-4">
            <h2 class="text-2xl font-semibold mb-4">{{ __('messages.create_press_release') }}</h2>
            <form action="{{ route('press_releases.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label for="createTitle" class="block text-sm font-medium text-gray-700">{{ __('messages.title') }}</label>
                    <input type="text" name="title" id="createTitle" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                </div>
                <div class="mb-4">
                    <label for="createContent" class="block text-sm font-medium text-gray-700">{{ __('messages.content') }}</label>
                    <textarea name="content" id="createContent" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required></textarea>
                </div>
                <div class="mb-4">
                    <label for="createImage" class="block text-sm font-medium text-gray-700">{{ __('messages.image') }}</label>
                    <input type="file" name="image" id="createImage" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
                <div class="flex justify-end">
                    <button type="button" class="bg-gray-500 text-white px-4 py-2 rounded-lg mr-2" onclick="closeModal('createPressReleaseModal')">{{ __('messages.cancel') }}</button>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg">{{ __('messages.create') }}</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Press Release Modal -->
    <div id="editPressReleaseModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6 mx-4">
            <h2 class="text-2xl font-semibold mb-4">{{ __('messages.edit_press_release') }}</h2>
            <form action="" method="POST" id="editPressReleaseForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label for="editTitle" class="block text-sm font-medium text-gray-700">{{ __('messages.title') }}</label>
                    <input type="text" name="title" id="editTitle" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                </div>
                <div class="mb-4">
                    <label for="editContent" class="block text-sm font-medium text-gray-700">{{ __('messages.content') }}</label>
                    <textarea name="content" id="editContent" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required></textarea>
                </div>
                <div class="mb-4">
                    <label for="editImage" class="block text-sm font-medium text-gray-700">{{ __('messages.image') }}</label>
                    <input type="file" name="image" id="editImage" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
                <div class="flex justify-end">
                    <button type="button" class="bg-gray-500 text-white px-4 py-2 rounded-lg mr-2" onclick="closeModal('editPressReleaseModal')">{{ __('messages.cancel') }}</button>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg">{{ __('messages.update') }}</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openEditModal(id, title, content) {
            const form = document.getElementById('editPressReleaseForm');
            form.action = `/press_releases/${id}`;
            document.getElementById('editTitle').value = title;
            document.getElementById('editContent').value = content;
            openModal('editPressReleaseModal');
        }

        function openModal(modalId) {
            document.getElementById(modalId).classList.remove('hidden');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }
    </script>
</body>
</html>
