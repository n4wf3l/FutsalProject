<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Press Releases | {{ $clubName }}</title>
    @if($logoPath)
        <link rel="icon" href="{{ $logoPath }}" type="image/png">
    @endif
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @vite('resources/css/app.css')
    <style>
        .press-release-card {
            background-color: #ffffff;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.2s ease-in-out;
            margin: 16px;
        }

        .press-release-card:hover {
            transform: translateY(-5px);
        }

        .press-release-content {
            padding: 1rem;
            text-align: center;
        }

        .press-release-title {
            font-size: 1.5rem;
            font-weight: bold;
            color: #333;
        }

        .press-release-date {
            color: #555;
            margin-top: 0.5rem;
            font-size: 1rem;
        }

        .press-release-view {
            margin-top: 1rem;
            color: #1D4ED8;
            font-weight: bold;
            text-decoration: none;
            display: inline-block;
        }

        .press-release-view:hover {
            text-decoration: underline;
        }

        .press-release-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 16px;
        }

        .no-press-releases-message {
            font-size: 1.5rem;
            color: #555;
            margin-top: 20px;
        }
    </style>
</head>
<body class="bg-gray-100">
    <x-navbar />

    <header class="text-center my-12" style="margin-top: 20px; font-size:60px;">
        <h1 class="text-6xl font-bold text-gray-900">Press Releases</h1>
        <div class="flex justify-center items-center mt-4">
            <p class="text-xl text-gray-600">View the latest press releases.</p>
        </div>
    </header>

    <main class="py-12 flex flex-col items-center">
        <div class="container mx-auto px-4 text-center">

            @if(session('success'))
                <div class="bg-green-500 text-white p-4 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @auth
            <div class="mb-6">
                <button onclick="openModal('createPressReleaseModal')"
                   class="text-white font-bold py-2 px-6 rounded-full transition duration-200 shadow-lg text-center"
                   style="background-color: {{ $primaryColor }};"
                   onmouseover="this.style.backgroundColor='{{ $secondaryColor }}'"
                   onmouseout="this.style.backgroundColor='{{ $primaryColor }}'">
                    Add Press Release
                </button>
            </div>
            @endauth

            <div class="press-release-container">
                @if($pressReleases->isEmpty())
                    <div class="no-press-releases-message">
                        No press releases available at the moment.
                    </div>
                @else
                    @foreach($pressReleases as $pressRelease)
                        <div class="press-release-card">
                            <div class="press-release-content">
                                <h2 class="press-release-title">{{ $pressRelease->title }}</h2>
                                <p class="press-release-date">{{ \Carbon\Carbon::parse($pressRelease->date)->format('d-m-Y') }}</p>
                                <a href="{{ route('press_releases.show', $pressRelease->slug) }}" class="press-release-view">Read more →</a>
                                
                                @auth
                                <div class="mt-4 flex justify-center space-x-4">
                                    <button onclick="openEditModal('{{ $pressRelease->id }}', '{{ $pressRelease->title }}', '{{ $pressRelease->content }}', '{{ $pressRelease->date }}')"
                                            class="text-yellow-500 font-bold py-2 px-6 rounded-full transition duration-200 shadow-lg text-center">
                                        Edit
                                    </button>
                                    <form action="{{ route('press_releases.destroy', $pressRelease->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this press release?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="text-white font-bold py-2 px-6 rounded-full transition duration-200 shadow-lg text-center"
                                                style="background-color: #DC2626;">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                                @endauth
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </main>

    <x-footer />

    <!-- Create Press Release Modal -->
    <div id="createPressReleaseModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-lg shadow-lg w-1/2 p-6">
            <h2 class="text-2xl font-semibold mb-4">Create Press Release</h2>
            <form action="{{ route('press_releases.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                    <input type="text" name="title" id="title" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                </div>
                <div class="mb-4">
                    <label for="content" class="block text-sm font-medium text-gray-700">Content</label>
                    <textarea name="content" id="content" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required></textarea>
                </div>
                <div class="mb-4">
                    <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
                    <input type="date" name="date" id="date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                </div>
                <div class="flex justify-end">
                    <button type="button" class="bg-gray-500 text-white px-4 py-2 rounded-lg mr-2" onclick="closeModal('createPressReleaseModal')">Cancel</button>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg">Create</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Press Release Modal -->
    <div id="editPressReleaseModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-lg shadow-lg w-1/2 p-6">
            <h2 class="text-2xl font-semibold mb-4">Edit Press Release</h2>
            <form action="" method="POST" id="editPressReleaseForm">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label for="editTitle" class="block text-sm font-medium text-gray-700">Title</label>
                    <input type="text" name="title" id="editTitle" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                </div>
                <div class="mb-4">
                    <label for="editContent" class="block text-sm font-medium text-gray-700">Content</label>
                    <textarea name="content" id="editContent" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required></textarea>
                </div>
                <div class="mb-4">
                    <label for="editDate" class="block text-sm font-medium text-gray-700">Date</label>
                    <input type="date" name="date" id="editDate" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                </div>
                <div class="flex justify-end">
                    <button type="button" class="bg-gray-500 text-white px-4 py-2 rounded-lg mr-2" onclick="closeModal('editPressReleaseModal')">Cancel</button>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg">Update</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(modalId) {
            document.getElementById(modalId).classList.remove('hidden');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }

        function openEditModal(id, title, content, date) {
            const form = document.getElementById('editPressReleaseForm');
            form.action = `/press_releases/${id}`;
            document.getElementById('editTitle').value = title;
            document.getElementById('editContent').value = content;
            document.getElementById('editDate').value = date;
            openModal('editPressReleaseModal');
        }
    </script>
</body>
</html>
