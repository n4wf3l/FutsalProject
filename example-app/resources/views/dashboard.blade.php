<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <x-navbar />

    <div class="container mx-auto mt-8">
        <h1 class="text-3xl font-bold mb-4">Dashboard</h1>

        <p>Welcome to your dashboard, {{ Auth::user()->name }}!</p>

        <!-- Exemple de boutons pour gérer les joueurs -->
        <div class="mt-4">
            <a href="{{ route('players.create') }}" class="bg-blue-500 text-white font-bold py-2 px-4 rounded">
                Add Player
            </a>
            <!-- Le bouton Edit pourrait être généré dynamiquement en fonction de l'ID d'un joueur -->
            <a href="{{ route('players.edit', 1) }}" class="bg-green-500 text-white font-bold py-2 px-4 rounded ml-4">
                Edit Player
            </a>
        </div>
    </div>
</body>
</html>
