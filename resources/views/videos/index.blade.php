<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.videos') }} | {{ $clubName }}</title>
    @if($logoPath)
        <link rel="icon" href="{{ $logoPath }}" type="image/png">
    @endif
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    <!-- Bootstrap CSS -->

    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="{{ __('messages.videos') }} | {{ $clubName }}" />
    <meta property="og:description" content="Découvrez les dernières vidéos de {{ $clubName }}." />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:image" content="{{ asset('storage/' . $videos->first()->image) }}" />
    <meta property="og:site_name" content="{{ $clubName }}" />
    <meta property="og:locale" content="{{ app()->getLocale() }}" />

    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="{{ __('messages.videos') }} | {{ $clubName }}" />
    <meta name="twitter:description" content="Découvrez les dernières vidéos de {{ $clubName }}." />
    <meta name="twitter:image" content="{{ asset('storage/' . $videos->first()->image) }}" />
    <meta name="twitter:url" content="{{ url()->current() }}" />

    <!-- Dublin Core Metadata -->
    <meta name="DC.title" content="{{ __('messages.videos') }} | {{ $clubName }}" />
    <meta name="DC.creator" content="{{ $clubName }}" />
    <meta name="DC.date" content="{{ now()->format('Y-m-d') }}" />
    <meta name="DC.language" content="{{ app()->getLocale() }}" />
    <meta name="DC.publisher" content="{{ $clubName }}" />
    <meta name="DC.format" content="text/html" />
    <meta name="DC.identifier" content="{{ url()->current() }}" />

    <style>
        
        /* Styles existants */
        .main-video-container {
            display: flex;
            gap: 20px;
            margin-bottom: 40px;
            flex-direction: column;
        }
        .main-video {
            flex: 2;
            background-color: #ffffff;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.2s ease;
        }
        .main-video:hover {
            transform: translateY(-5px);
        }
        .main-video a {
            display: block;
            color: inherit;
            text-decoration: none;
            height: 100%;
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
        .recent-videos {
            flex: 1;
            background-color: #ffffff;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            padding: 20px;
            position: relative;
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
            color: {{ $secondaryColor }};
            margin-bottom: 10px;
            text-decoration: none;
            padding-bottom: 5px;
        }
        .recent-videos a:hover {
            text-decoration: underline;
        }
        .recent-videos hr {
            border: none;
            border-top: 1px solid #e2e8f0;
            margin: 10px 0;
        }
        .recent-video-date {
            font-size: 0.875rem;
            color: #4a5568;
            white-space: nowrap;
        }
        .recent-videos-container {
            margin-top: 40px;
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
        }
        @media (min-width: 768px) {
            .recent-videos-container {
                grid-template-columns: 1fr 1fr;
            }
        }
        @media (min-width: 1024px) {
            .main-video-container {
                flex-direction: row;
            }
            .recent-videos-container {
                grid-template-columns: repeat(3, 1fr);
            }
        }
        .video-item {
            display: flex;
            align-items: center;
            background-color: #ffffff;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            padding: 20px;
            transition: transform 0.2s ease, border-color 0.2s ease;
            border: 1px solid transparent;
            text-decoration: none;
            color: inherit;
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
            display: flex;
            flex-direction: column;
        }
        .video-item-title {
            font-size: 1.25rem;
            font-weight: bold;
            margin-bottom: 10px;
            color: {{ $primaryColor }};
            text-decoration: none;
        }
        .video-item-title:hover {
            text-decoration: underline;
        }
        .video-item-meta {
            color: #4a5568;
            font-size: 0.875rem;
            margin-bottom: 10px;
        }
        .video-item-description {
            font-size: 1rem;
            color: #333;
            line-height: 1.5;
        }
    </style>
</head>
<body class="bg-gray-100" @if($backgroundImage) style="background: url('{{ asset('storage/' . $backgroundImage->image_path) }}') no-repeat center center fixed; background-size: cover;" @endif>

    <x-navbar />
    <div class="bootstrap-only">
        <header class="text-center my-12">
            <x-page-title subtitle="{{ __('messages.check_latest_videos') }}">
                {{ __('messages.recent_videos') }}
            </x-page-title>
            @auth
                <button 
                    type="button"
                    style="background-color: {{ $primaryColor }}; color: white; font-family: 'Bebas Neue', sans-serif; font-weight: bold; padding: 10px 20px; border: none; border-radius: 50px; cursor: pointer; transition: background-color 0.3s ease, transform 0.2s ease; box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1); letter-spacing: 1px;"
                    onmouseover="this.style.backgroundColor='{{ $secondaryColor }}'; this.style.transform='scale(1.05)';"
                    onmouseout="this.style.backgroundColor='{{ $primaryColor }}'; this.style.transform='scale(1)';"
                    data-bs-toggle="modal"
                    data-bs-target="#addVideoModal">
                    {{ __('messages.add_video') }}
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
                <form action="{{ route('videos.destroy', $videos->first()->id) }}" method="POST" style="position: absolute; top: 10px; right: 10px;" onsubmit="return confirm('{{ __('messages.delete_confirmation') }}');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="delete-button">&times;</button>
                </form>
            </div>

            <!-- Recent Videos List -->
            <div class="recent-videos" data-aos="fade-left">
                <h3>{{ __('messages.recent_videos_list') }}</h3>
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
            <p class="text-gray-600 text-center" style="margin: 0 auto;">{{ __('messages.no_videos_available') }}</p>
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
                <form action="{{ route('videos.destroy', $video->id) }}" method="POST" style="position: absolute; top: 10px; right: 10px;" onsubmit="return confirm('{{ __('messages.delete_confirmation') }}');">
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
    </div>

    <x-footerforhome />

    <!-- Modal to Add Video -->
    <div class="modal fade" id="addVideoModal" tabindex="-1" aria-labelledby="addVideoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('videos.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addVideoModalLabel">{{ __('messages.add_new_video') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="title">{{ __('messages.title') }}</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        <div class="form-group mt-3">
                            <label for="description">{{ __('messages.description') }}</label>
                            <textarea name="description" class="form-control"></textarea>
                        </div>
                        <div class="form-group mt-3">
                            <label for="url">{{ __('messages.video_url') }}</label>
                            <input type="url" name="url" class="form-control" required>
                        </div>
                        <div class="form-group mt-3">
                            <label for="image">{{ __('messages.image') }}</label>
                            <input type="file" name="image" class="form-control-file" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('messages.close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('messages.save_video') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
