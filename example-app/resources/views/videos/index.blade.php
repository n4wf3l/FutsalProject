<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Videos | {{ $clubName }}</title>
    @if($logoPath)
        <link rel="icon" href="{{ $logoPath }}" type="image/png">
    @endif
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Page Styles */
        .main-video-container {
            display: flex;
            gap: 20px;
            margin-bottom: 40px;
            position: relative;
        }

        .main-video {
            flex: 2;
            background-color: #ffffff;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.2s ease;
            position: relative;
        }

        .main-video:hover {
            transform: translateY(-5px);
        }

        .main-video img {
            width: 100%;
            object-fit: cover;
            height: auto;
            max-height: 500px;
        }

        .main-video-content {
            padding: 20px;
        }

        .delete-button {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: #FF0000;
            color: white;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            width: 30px;
            height: 30px;
            display: flex;
            justify-content: center;
            align-items: center;
            transition: background-color 0.3s ease;
        }

        .delete-button:hover {
            background-color: #cc0000;
        }

        .recent-videos {
            flex: 1;
            background-color: #ffffff;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .recent-videos h3 {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 15px;
            border-bottom: 3px solid {{ $secondaryColor }};
            padding-bottom: 5px;
        }

        .recent-videos a {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            text-decoration: none;
            padding-bottom: 5px;
            color: inherit;
        }

        .recent-videos a:hover {
            text-decoration: underline;
            color: inherit;
        }

        .recent-videos hr {
            border: none;
            border-top: 1px solid #e2e8f0;
            margin: 10px 0;
        }

        .video-item {
            display: flex;
            align-items: center;
            background-color: #ffffff;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            padding: 20px;
            transition: transform 0.2s ease;
            border: 1px solid transparent;
            position: relative;
        }

        .video-item:hover {
            transform: translateY(-5px);
            border-color: {{ $primaryColor }};
        }

        .video-item img {
            width: 150px;
            height: 100px;
            object-fit: cover;
            border-radius: 5px;
            margin-right: 20px;
        }

        .video-item-content {
            flex: 1;
        }

        .video-item-title {
            font-size: 1.25rem;
            font-weight: bold;
            margin-bottom: 10px;
            color: {{ $primaryColor }};
        }

        .video-item-meta {
            color: #4a5568;
            font-size: 0.875rem;
            margin-bottom: 10px;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .main-video-container {
                flex-direction: column;
            }

            .main-video {
                max-width: 100%;
                margin-bottom: 20px;
            }

            .recent-videos {
                max-width: 100%;
                padding: 10px;
            }

            .video-item img {
                width: 120px;
                height: 80px;
            }

            .video-item {
                flex-direction: column;
                align-items: flex-start;
            }

            .video-item-content {
                margin-top: 10px;
            }

            .video-item-title {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body class="bg-gray-100" @if($backgroundImage) style="background: url('{{ asset('storage/' . $backgroundImage->image_path) }}') no-repeat center center fixed; background-size: cover;" @endif>

    <x-navbar />

    <header class="text-center my-12">
        <x-page-title subtitle="🎥 Check out our latest videos!">
            Recent Videos
        </x-page-title>
        @auth
            <button 
                type="button" 
                style="background-color: {{ $primaryColor }}; 
               color: white; 
               font-family: 'Bebas Neue', sans-serif;
               font-weight: bold; 
               padding: 5px 10px; 
               border: none; 
               border-radius: 50px; 
               cursor: pointer; 
               transition: background-color 0.3s ease, transform 0.2s ease; 
               box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);"
        onmouseover="this.style.backgroundColor='{{ $secondaryColor }}'; this.style.transform='scale(1.05)';"
        onmouseout="this.style.backgroundColor='{{ $primaryColor }}'; this.style.transform='scale(1)';"
                data-bs-toggle="modal"
                data-bs-target="#addVideoModal">
                Add Video
            </button>
        @endauth
    </header>

    <div class="container mx-auto py-12">
    <div class="main-video-container">
        @if($videos->isNotEmpty())
            <!-- Main Video -->
            <div class="main-video" data-aos="fade-right">
                <a href="{{ $videos->first()->url }}" target="_blank">
                    @if($videos->first()->image)
                        <img src="{{ asset('storage/' . $videos->first()->image) }}" alt="{{ $videos->first()->title }}">
                    @endif
                    <div class="main-video-content">
                        <h2 class="text-3xl font-bold mb-2" style="color: {{ $primaryColor }};">
                            <strong>{{ $videos->first()->title }}</strong>
                        </h2>
                        <p class="text-gray-600 mb-4">
                            {!! \Illuminate\Support\Str::limit(strip_tags($videos->first()->description, '<b><i><strong><em><ul><li><ol>'), 200) !!}
                        </p>
                    </div>
                </a>

                <!-- Delete Button for Main Video -->
                <form action="{{ route('videos.destroy', $videos->first()->id) }}" method="POST" style="position: absolute; top: 10px; right: 10px;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="delete-button">&times;</button>
                </form>
            </div>

            <!-- Recent Videos List -->
            <div class="recent-videos" data-aos="fade-left">
                <h3>🎬 Recent Videos</h3>
                @foreach($videos->skip(1) as $video)
                    <a href="{{ $video->url }}" target="_blank">
                        <span>{{ $video->title }}</span>
                        <span class="recent-video-date">{{ $video->created_at->format('d/m') }}</span>
                    </a>
                    <hr>
                @endforeach

                <!-- Pagination Links -->
                <div class="mt-4">
                    {{ $videos->links('vendor.pagination.simple') }}
                </div>
            </div>
        @else
            <p class="text-center text-gray-600">No videos available.</p>
        @endif
    </div>

    <!-- Recent Videos Below the Main Video -->
    <div class="recent-videos-container" data-aos="fade-up-right">
        @foreach($videos->skip(1) as $video)
            <div class="video-item">
                <a href="{{ $video->url }}" target="_blank">
                    @if($video->image)
                        <img src="{{ asset('storage/' . $video->image) }}" alt="{{ $video->title }}">
                    @endif
                    <div class="video-item-content">
                        <h2 class="video-item-title">{{ $video->title }}</h2>
                        <div class="video-item-meta">
                            <span>{{ $video->created_at->format('d/m') }} - </span>
                            <span>
                                {{ \Illuminate\Support\Str::limit(strip_tags($video->description), 100) }}
                            </span>
                        </div>
                    </div>
                </a>

                <!-- Delete Button for Recent Videos -->
                <form action="{{ route('videos.destroy', $video->id) }}" method="POST" style="position: absolute; top: 10px; right: 10px;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="delete-button">&times;</button>
                </form>
            </div>
        @endforeach

        <!-- Pagination Links -->
        <div class="mt-4">
            {{ $videos->links('vendor.pagination.simple') }}
        </div>
    </div>
</div>

    <!-- Add Video Modal -->
    <div class="modal fade" id="addVideoModal" tabindex="-1" aria-labelledby="addVideoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('videos.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addVideoModalLabel">Add New Video</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        <div class="form-group mt-3">
                            <label for="description">Description</label>
                            <textarea name="description" class="form-control"></textarea>
                        </div>
                        <div class="form-group mt-3">
                            <label for="url">Video URL</label>
                            <input type="url" name="url" class="form-control" required>
                        </div>
                        <div class="form-group mt-3">
                            <label for="image">Image</label>
                            <input type="file" name="image" class="form-control-file" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Video</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <x-footerforhome />
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
