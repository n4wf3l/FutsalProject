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
    <style>
        .gallery-card {
            background-color: #ffffff;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.2s ease-in-out;
            margin-bottom: 24px;
            width: 100%;
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
        }

        .gallery-description {
            color: #555;
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
        }

        .button-group {
            display: flex;
            justify-content: space-between;
            margin-top: 1rem;
        }

        .edit-button {
            background-color: #fbbf24;
            color: #fff;
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: bold;
            text-decoration: none;
        }

        .delete-button {
            background-color: #dc2626;
            color: #fff;
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: bold;
            text-decoration: none;
        }
    </style>
</head>
<body class="bg-gray-100">
    <x-navbar />

    <header class="text-center my-12">
        <h1 class="text-6xl font-bold text-gray-900">Gallery</h1>
        <div class="flex justify-center items-center mt-4">
            <p class="text-xl text-gray-600">View the latest photo albums.</p>
        </div>
    </header>

    <main class="container mx-auto px-4">
        @auth
            <!-- Button to open the modal to create a new gallery -->
            <div class="text-center">
                <button onclick="openModal('createGalleryModal')" class="text-white font-bold py-2 px-6 rounded-full transition duration-200 shadow-lg text-center" style="background-color: {{ $primaryColor }};">Create Gallery</button>
            </div>
        @endauth

        <div class="grid grid-cols-3 gap-6 mt-8">
            @foreach($galleries as $gallery)
                <div class="gallery-card">
                    @if($gallery->cover_image)
                        <div class="gallery-image">
                            <img src="{{ asset('storage/' . $gallery->cover_image) }}" alt="{{ $gallery->name }}">
                        </div>
                    @endif
                    <div class="gallery-content">
                        <h2 class="gallery-title">{{ $gallery->name }}</h2>
                        <p class="gallery-description">{{ $gallery->description }}</p>
                        <a href="{{ route('galleries.show', $gallery->id) }}" class="text-blue-500 font-bold">View Album</a>
                        
                        @auth
                        <div class="button-group mt-4">
                            <button onclick="openEditModal('{{ $gallery->id }}', '{{ $gallery->name }}', '{{ $gallery->description }}')" class="edit-button">Edit</button>
                            
                            <form action="{{ route('galleries.destroy', $gallery->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this gallery?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="delete-button">Delete</button>
                            </form>
                        </div>
                        @endauth
                    </div>
                </div>
            @endforeach
        </div>

        <div class="pagination">
    {{ $galleries->links() }}
</div>
    </main>

    <x-footer />

    <!-- Create Gallery Modal -->
    <div id="createGalleryModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-lg shadow-lg w-1/2 p-6">
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
        <div class="bg-white rounded-lg shadow-lg w-1/2 p-6">
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
    Dropzone.options.photoDropzone = {
        paramName: "photos", // The name that will be used to transfer the files
        maxFilesize: 2, // MB
        maxFiles: 10, // Limiting the maximum number of files
        acceptedFiles: "image/*",
        addRemoveLinks: true,
        dictDefaultMessage: "Drag your photos here or click to upload",
        init: function () {
            this.on("success", function (file, response) {
                console.log("Upload success for file:", file);
                console.log("Server response:", response);

                // Traite la réponse pour chaque photo
                response.forEach(function(photoData) {
                    console.log("Processing photo data:", photoData);
                    if (photoData.image) {
                        addPhotoToGallery(photoData);
                    } else {
                        console.error("No image data found in photoData:", photoData);
                    }
                });
            });
            this.on("error", function (file, response) {
                console.log("Failed to upload:", file.name);
                console.error("Error response from server:", response);
            });
        }
    };

    function addPhotoToGallery(photoData) {
        console.log("Adding photo to gallery:", photoData);

        var galleryContainer = document.getElementById('galleryPhotosContainer');
        var photoItem = document.createElement('div');
        photoItem.className = 'photo-item';

        var img = document.createElement('img');
        img.src = '/storage/' + photoData.image; // S'assure que le chemin est correct
        img.alt = photoData.caption || 'Photo';
        img.className = 'w-full h-auto';

        var caption = document.createElement('p');
        caption.className = 'text-center mt-2';
        caption.textContent = photoData.caption || '';

        photoItem.appendChild(img);
        photoItem.appendChild(caption);

        galleryContainer.appendChild(photoItem);
        console.log("Photo added successfully to the gallery.");
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
