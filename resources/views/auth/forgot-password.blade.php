<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password | {{ $clubName }}</title>
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
        .reset-password-container {
            background-color: white;
            padding: 2rem;
            border-radius: 0.5rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            text-align: center;
        }
        .reset-password-container img {
            display: block;
            margin: 0 auto 1.5rem; /* Centrer l'image et ajouter un espace en bas */
            max-width: 150px;
        }
        .reset-password-container button {
            width: 100%;
            background-color: {{ $primaryColor }};
            color: white;
            padding: 0.75rem;
            border-radius: 0.5rem;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        .reset-password-container button:hover {
            background-color: {{ $secondaryColor }};
        }
        .reset-password-container a {
            color: #6366f1;
            text-decoration: none;
            font-size: 0.875rem;
        }
        .reset-password-container a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="reset-password-container">
    <!-- Logo Centré -->
    @if($logoPath)
        <img src="{{ asset($logoPath) }}" alt="Site Logo">
    @endif

    <!-- Formulaire de Réinitialisation de Mot de Passe -->
    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Texte Introduction -->
        <div class="mb-4 text-sm text-gray-600">
            {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
        </div>

        <!-- Adresse Email -->
        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input id="email" type="email" name="email" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" value="{{ old('email') }}" required autofocus>
            @error('email')
            <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <!-- Bouton de Réinitialisation -->
        <button type="submit">
            Envoyer le lien de réinitialisation
        </button>
    </form>
</div>

</body>
</html>
