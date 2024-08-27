<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gallery | {{ $clubName }}</title>
    @if($logoPath)
        <link rel="icon" href="{{ $logoPath }}" type="image/png">
    @endif
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    <style>
        .gallery-card {
            background-color: #ffffff;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.2s ease-in-out;
            margin-bottom: 24px;
            width: 100%;
            position: relative;
        }

        .gallery-card:hover {
            transform: translateY(-5px);
        }

        .gallery-image img {
            width: 100%;
            height: 250px;
            object-fit: cover;
        }

        .gallery-content {
            padding: 1rem;
        }

        .gallery-title {
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

        .gallery-description {
            color: #555;
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
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

        /* Responsive adjustments */
        @media (max-width: 1024px) {
            .grid-cols-3 {
                grid-template-columns: repeat(2, 1fr);
            }

            .gallery-image img {
                height: 200px;
            }
        }

        @media (max-width: 768px) {
            .grid-cols-3 {
                grid-template-columns: repeat(1, 1fr);
            }

            .gallery-image img {
                height: 150px;
            }

            .gallery-title {
                font-size: 1rem;
            }

            .gallery-description {
                font-size: 0.75rem;
            }

            .gallery-content {
                padding: 0.75rem;
            }
        }

        @media (max-width: 480px) {
            .gallery-image img {
                height: 120px;
            }

            .gallery-title {
                font-size: 0.875rem;
            }

            .gallery-description {
                font-size: 0.75rem;
            }

            .gallery-content {
                padding: 0.5rem;
            }
        }
    </style>
</head>
<body class="bg-gray-100">
    <x-navbar />

    <header class="text-center my-12">
    <x-page-title subtitle="📸 Dive into our collection of photo albums capturing the excitement and energy of past futsal events.">
    Gallery
</x-page-title>
    </header>

    <main class="container mx-auto px-4">
        @auth
            <!-- Button to open the modal to create a new gallery -->
            <div class="text-center mb-6">
                <button onclick="openModal('createGalleryModal')" class="text-white font-bold py-2 px-6 rounded-full transition duration-200 shadow-lg text-center" style="background-color: {{ $primaryColor }};">Create Gallery</button>
            </div>
        @endauth

        <div class="grid grid-cols-3 gap-6 mt-8" data-aos="flip-down">
    @if($galleries->isEmpty())
        <p class="text-center text-gray-600 col-span-3">There are currently no albums.</p>
    @else
        @foreach($galleries as $gallery)
            <div class="gallery-card">
                @if($gallery->cover_image)
                    <div class="gallery-image">
                        <a href="{{ route('galleries.show', $gallery->id) }}">
                            <img src="{{ asset('storage/' . $gallery->cover_image) }}" alt="{{ $gallery->name }}">
                        </a>
                        @auth
                        <form action="{{ route('galleries.destroy', $gallery->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this gallery?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="delete-button-x">X</button>
                        </form>
                        @endauth
                    </div>
                @endif
                <div class="gallery-content">
                    <h2 class="gallery-title">
                        {{ $gallery->name }}
                        @auth
                            <button onclick="openEditModal('{{ $gallery->id }}', '{{ $gallery->name }}', '{{ $gallery->description }}')" class="edit-button-emoji">🛠️</button>
                        @endauth
                    </h2>
                    <p class="gallery-description">{{ $gallery->description }}</p>
                    <a href="{{ route('galleries.show', $gallery->id) }}" class="text-blue-500 font-bold">View Album</a>
                </div>
            </div>
        @endforeach
    @endif
</div>


        <div class="pagination mt-8">
            {{ $galleries->links() }}
        </div>
    </main>

    <x-footer />

    <!-- Create Gallery Modal -->
    <div id="createGalleryModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-lg shadow-lg w-11/12 sm:w-3/4 lg:w-1/2 p-6">
            <h2 class="text-2xl font-semibold mb-4">Create Gallery</h2>
            <form action="{{ route('galleries.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" name="name" id="name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                </div>
                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" id="description" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                </div>
                <div class="mb-4">
                    <label for="cover_image" class="block text-sm font-medium text-gray-700">Cover Image</label>
                    <input type="file" name="cover_image" id="cover_image" accept="image/*" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
                <div class="flex justify-end">
                    <button type="button" class="bg-gray-500 text-white px-4 py-2 rounded-lg mr-2" onclick="closeModal('createGalleryModal')">Cancel</button>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg">Create</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Gallery Modal -->
    <div id="editGalleryModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-lg shadow-lg w-11/12 sm:w-3/4 lg:w-1/2 p-6">
            <h2 class="text-2xl font-semibold mb-4">Edit Gallery</h2>
            <form action="" method="POST" id="editGalleryForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label for="editName" class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" name="name" id="editName" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                </div>
                <div class="mb-4">
                    <label for="editDescription" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" id="editDescription" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                </div>
                <div class="mb-4">
                    <label for="editCoverImage" class="block text-sm font-medium text-gray-700">Cover Image</label>
                    <input type="file" name="cover_image" id="editCoverImage" accept="image/*" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
                <div class="flex justify-end">
                    <button type="button" class="bg-gray-500 text-white px-4 py-2 rounded-lg mr-2" onclick="closeModal('editGalleryModal')">Cancel</button>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg">Update</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openEditModal(id, name, description) {
            const form = document.getElementById('editGalleryForm');
            form.action = `/galleries/${id}`;
            document.getElementById('editName').value = name;
            document.getElementById('editDescription').value = description;
            openModal('editGalleryModal');
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
