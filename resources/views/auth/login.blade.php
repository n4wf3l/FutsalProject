<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.login') }} | {{ $clubName }}</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @vite('resources/css/app.css')
    <style>
        body {
            background-color: #f8fafc;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background-color: white;
            padding: 2rem;
            border-radius: 0.5rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            text-align: center;
        }
        .login-container img {
            display: block;
            margin: 0 auto 1.5rem;
            max-width: 150px;
        }
        .login-container button {
            width: 100%;
            background-color: {{ $primaryColor }};
            color: white;
            padding: 0.75rem;
            border-radius: 0.5rem;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        .login-container button:hover {
            background-color: {{ $secondaryColor }};
        }
        .login-container a {
            color: #6366f1;
            text-decoration: none;
            font-size: 0.875rem;
        }
        .login-container a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="login-container">
    <!-- Logo Centré -->
    @if($logoPath)
        <img src="{{ asset($logoPath) }}" alt="{{ __('messages.site_logo') }}">
    @endif

    <!-- Formulaire de Connexion -->
    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Adresse Email -->
        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700">{{ __('messages.email') }}</label>
            <input id="email" type="email" name="email" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" value="{{ old('email') }}" required autofocus>
            @error('email')
            <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <!-- Mot de Passe -->
        <div class="mb-4">
            <label for="password" class="block text-sm font-medium text-gray-700">{{ __('messages.password') }}</label>
            <input id="password" type="password" name="password" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" required>
            @error('password')
            <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <!-- Se Souvenir de Moi -->
        <div class="mb-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ml-2 text-sm text-gray-600">{{ __('messages.remember_me') }}</span>
            </label>
        </div>

        <!-- Bouton de Connexion -->
        <button type="submit">
            {{ __('messages.login') }}
        </button>

        <!-- Lien Mot de Passe Oublié -->
        @if (Route::has('password.request'))
            <div class="mt-4">
                <a href="{{ route('password.request') }}">{{ __('messages.forgot_password') }}</a>
            </div>
        @endif
    </form>
</div>

</body>
</html>
