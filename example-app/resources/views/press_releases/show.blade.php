<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pressRelease->title }} | {{ $clubName }}</title>
    @if($logoPath)
        <link rel="icon" href="{{ $logoPath }}" type="image/png">
    @endif
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @vite('resources/css/app.css')
    <style>
        .publication-info {
            color: {{ $primaryColor }};
            font-weight: bold;
            display: flex;
            align-items: center;
            margin-top: 20px;
        }

        .publication-info span {
            color: #333;
            margin-left: 10px;
        }

        .content-paragraph {
            margin-top: 20px;
            line-height: 1.8;
            color: #555;
        }
    </style>
</head>
<body class="bg-gray-100">
    <x-navbar />

    <header class="text-center my-12">
        <h1 class="text-6xl font-bold text-gray-900">{{ $pressRelease->title }}</h1>
    </header>

    <main class="py-12 flex flex-col items-center">
        <div class="container mx-auto px-4">
            <div class="bg-white p-6 rounded-lg shadow-lg">
                @if($pressRelease->image)
                    <img src="{{ asset('storage/' . $pressRelease->image) }}" alt="{{ $pressRelease->title }}" class="w-full h-auto rounded mb-4">
                @endif

                <div class="publication-info">
                    COMMUNIQUÉS /
                    <span>{{ \Carbon\Carbon::parse($pressRelease->created_at)->format('l j F Y') }}</span>
                </div>

                <p class="content-paragraph">
                    {!! nl2br(e($pressRelease->content)) !!}
                </p>
            </div>
        </div>
    </main>

    <x-footer />
</body>
</html>
