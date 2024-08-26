<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $gallery->name }} | {{ $clubName }}</title>
    @if($logoPath)
        <link rel="icon" href="{{ $logoPath }}" type="image/png">
    @endif
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @vite('resources/css/app.css')

    <!-- CSS for the gallery grid, modals, and preview images -->
    <style>
        /* Gallery grid layout */
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); /* Ensure items fill available space */
            gap: 10px;
            grid-auto-flow: dense; /* Fill gaps effectively */
        }

        /* Individual gallery item style */
        .gallery-item {
            position: relative;
            overflow: hidden;
            aspect-ratio: 1; /* Ensure items are squares */
        }

        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover; /* Ensures the images cover the entire area */
            display: block;
            border-radius: 8px;
            transition: transform 0.3s ease;
        }

        .gallery-item:hover img {
            transform: scale(1.05);
        }

        /* Delete button style for each photo */
        .delete-photo {
            position: absolute;
            top: 5px;
            right: 5px;
            background-color: rgba(255, 0, 0, 0.7);
            color: white;
            border: none;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 10;
        }

        /* Modal overlay style */
        .modal {
            display: none;
            position: fixed;
            z-index: 1200;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.8);
            justify-content: center;
            align-items: center;
        }

        /* Modal content style */
        .modal-content-wrapper {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            height: 100%;
        }

        /* Close button style for modals */
        .close {
            position: absolute;
            top: 20px;
            right: 30px;
            color: #fff;
            font-size: 40px;
            font-weight: bold;
            cursor: pointer;
            z-index: 400; /* Ensure it is above other elements */
        }

        .close:hover,
        .close:focus {
            color: #bbb;
            text-decoration: none;
            cursor: pointer;
        }

        /* Modal content style */
        .modal-content {
            max-width: 90%;
            max-height: 90%;
            margin: auto;
            display: block;
            opacity: 0; /* Initially transparent */
            transition: opacity 0.5s ease; /* Transition for opacity */
        }

        /* Navigation buttons for image modal */
        .prev,
        .next {
            cursor: pointer;
            position: absolute;
            top: 50%;
            padding: 16px;
            color: white;
            font-weight: bold;
            font-size: 20px;
            transition: 0.6s ease;
            user-select: none;
            transform: translateY(-50%);
            z-index: 400; /* Ensure buttons are above images but below the close button */
        }

        .prev {
            left: 10px;
            border-radius: 0 3px 3px 0;
        }

        .next {
            right: 10px;
            border-radius: 3px 0 0 3px;
        }

        .prev:hover,
        .next:hover {
            background-color: rgba(0, 0, 0, 0.8);
        }

        /* Preview image styles for upload modal */
        .preview-image {
            max-width: 100px;
            max-height: 100px;
            object-fit: cover;
            margin-right: 10px;
            margin-bottom: 10px;
        }

        .photo-item {
            margin-bottom: 1rem;
        }

        .image-caption {
            position: absolute;
            bottom: 10px;
            right: 10px;
            color: white;
            background-color: rgba(0, 0, 0, 0.5);
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 12px;
            font-weight: bold;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .gallery-grid {
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            }

            .modal-content {
                max-width: 100%;
                max-height: 100%;
            }

            .close {
                font-size: 30px;
                top: 10px;
                right: 20px;
            }

            .prev,
            .next {
                font-size: 18px;
                padding: 10px;
            }
        }

        @media (max-width: 480px) {
            .gallery-grid {
                grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            }

            .modal-content {
                max-width: 100%;
                max-height: 100%;
            }

            .close {
                font-size: 25px;
                top: 5px;
                right: 15px;
            }

            .prev,
            .next {
                font-size: 16px;
                padding: 8px;
            }

            .image-caption {
                font-size: 10px;
                padding: 3px 5px;
            }
        }
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-100">
    <!-- Navigation Bar -->
    <x-navbar />

    <header class="text-center my-12">
    <x-page-title subtitle="{{ $gallery->description }}">
    {{ $gallery->name }}
</x-page-title>
    </header>

    <!-- Button for adding photos (visible only to authenticated users) -->
    @auth
        <div class="mt-8 text-center">
            <button onclick="openAddPhotosModal()" class="text-white font-bold py-2 px-6 rounded-full transition duration-200 shadow-lg text-center" style="margin-bottom:50px; background-color: {{ $primaryColor }};"
                    onmouseover="this.style.backgroundColor='{{ $secondaryColor }}'"
                    onmouseout="this.style.backgroundColor='{{ $primaryColor }}'">Add Photos</button>
        </div>
    @endauth

    <!-- Main content section with gallery grid -->
    <main class="container mx-auto px-4 mb-20">
        <div class="gallery-grid">
            @foreach($photos as $photo)
                <div class="gallery-item">
                    <img src="{{ asset('storage/' . $photo->image) }}" alt="{{ $photo->caption }}" onclick="openImageModal({{ $loop->index }})">
                    @if($photo->caption)
                        <p class="text-center mt-2">{{ $photo->caption }}</p>
                    @endif
                    @auth
                    <button class="delete-photo" onclick="deletePhoto('{{ route('galleries.photos.destroy', [$gallery->id, $photo->id]) }}')">×</button>
                    @endauth
                </div>
            @endforeach
        </div>
    </main>

    <!-- Modal for image preview -->
    <div id="imageModal" class="modal">
        <span class="close" onclick="closeImageModal()">&times;</span>
        <div class="modal-content-wrapper">
            <img class="modal-content" id="modalImage">
            <div id="caption" class="image-caption"></div> <!-- Caption div -->
        </div>
        <a class="prev" onclick="changeSlide(-1)">&#10094;</a>
        <a class="next" onclick="changeSlide(1)">&#10095;</a>
    </div>

    <!-- Modal for adding photos -->
    <div id="addPhotosModal" class="modal">
        <div class="modal-content bg-white rounded-lg p-6">
            <span class="close" onclick="closeAddPhotosModal()">&times;</span>
            <h2 class="text-2xl font-semibold mb-4">Add Photos to Album</h2>
            <form action="{{ route('galleries.photos.storeMultiple', $gallery->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label for="photos" class="block text-sm font-medium text-gray-700">Select up to 10 photos</label>
                    <input type="file" name="photos[]" id="photos" multiple accept="image/*" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" onchange="previewPhotos()">
                </div>
                
                <!-- Single Caption Input for all photos -->
                <div class="mb-4">
                    <label for="captions" class="block text-sm font-medium text-gray-700">Photographer</label>
                    <input type="text" name="captions" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Enter the photographer's name">
                </div>

                <div id="photoPreview" class="grid grid-cols-5 gap-2 mb-4"></div>
                <div class="flex justify-end">
                    <button type="button" class="bg-gray-500 text-white px-4 py-2 rounded-lg mr-2" onclick="closeAddPhotosModal()">Cancel</button>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg">Upload Photos</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <x-footer />

    <!-- JavaScript for modals and gallery functionality -->
    <script>
        let currentSlide = 0;
        const photos = @json($photos);

        // Functions for image modal
        function openImageModal(index) {
            currentSlide = index;
            document.getElementById('imageModal').style.display = "flex";
            showSlide(currentSlide);
        }

        function closeImageModal() {
            document.getElementById('imageModal').style.display = "none";
        }

        function showSlide(index) {
            const modalImg = document.getElementById('modalImage');
            const captionText = document.getElementById('caption');

            // Masque l'image actuelle en définissant l'opacité à 0
            modalImg.style.opacity = 0;

            // Après un court délai pour permettre à l'image actuelle de disparaître, charge la nouvelle image
            setTimeout(() => {
                modalImg.src = '{{ asset('storage/') }}/' + photos[index].image;
                captionText.textContent = photos[index].caption ? `© ${photos[index].caption}` : '';

                // Affiche la nouvelle image en définissant l'opacité à 1
                modalImg.style.opacity = 1;
            }, 200); // Correspond au temps de transition défini dans CSS
        }

        function changeSlide(direction) {
            currentSlide += direction;

            if (currentSlide >= photos.length) {
                currentSlide = 0;
            } else if (currentSlide < 0) {
                currentSlide = photos.length - 1;
            }

            showSlide(currentSlide);
        }

        // Function to delete a photo
        function deletePhoto(deleteUrl) {
            if (confirm('Are you sure you want to delete this photo?')) {
                // Create a hidden form
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = deleteUrl;

                // Ensure the CSRF token is present in the meta tag
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                if (!csrfToken) {
                    console.error('CSRF token not found!');
                    return;
                }

                // Add the CSRF token
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = csrfToken;
                form.appendChild(csrfInput);

                // Add the DELETE method
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';
                form.appendChild(methodInput);

                // Append the form to the body and submit it
                document.body.appendChild(form);
                form.submit();
            }
        }

        // Functions for add photos modal
        function openAddPhotosModal() {
            document.getElementById('addPhotosModal').style.display = "flex";
        }

        function closeAddPhotosModal() {
            document.getElementById('addPhotosModal').style.display = "none";
        }

        function previewPhotos() {
            const input = document.getElementById('photos');
            const preview = document.getElementById('photoPreview');
            preview.innerHTML = '';

            Array.from(input.files).forEach((file) => {
                const reader = new FileReader();

                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.classList.add('photo-item');

                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.classList.add('preview-image');
                    div.appendChild(img);

                    preview.appendChild(div);
                };

                reader.readAsDataURL(file);
            });
        }

        let touchStartX = 0;
    let touchEndX = 0;

    function handleSwipe() {
        if (touchEndX < touchStartX) {
            changeSlide(1); // Swipe left, go to next slide
        }
        if (touchEndX > touchStartX) {
            changeSlide(-1); // Swipe right, go to previous slide
        }
    }

    document.getElementById('imageModal').addEventListener('touchstart', function(event) {
        touchStartX = event.changedTouches[0].screenX;
    }, false);

    document.getElementById('imageModal').addEventListener('touchend', function(event) {
        touchEndX = event.changedTouches[0].screenX;
        handleSwipe();
    }, false);
    </script>
</body>
</html>
