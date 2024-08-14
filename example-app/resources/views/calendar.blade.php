<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Site</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100">
  <x-navbar />
  <header class="bg-red-500 py-8">
        <div class="container mx-auto text-center">
            <h1 class="text-4xl font-bold text-white">Classement Futsal D1</h1>
        </div>
    </header>

    <main class="py-12">
        <div class="container mx-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-red-500 text-white">
                        <th class="p-3 text-center">#</th>
                        <th class="p-3">Équipe</th>
                        <th class="p-3 text-center">Pts</th>
                        <th class="p-3 text-center">Jo</th>
                        <th class="p-3 text-center">G</th>
                        <th class="p-3 text-center">N</th>
                        <th class="p-3 text-center">P</th>
                        <th class="p-3 text-center">Diff</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Exemple d'une ligne de classement -->
                    <tr class="bg-white">
                        <td class="p-3 text-center font-bold text-red-500">01</td>
                        <td class="p-3 flex items-center">
                            <img src="path-to-logo" alt="Logo" class="h-8 w-8 mr-3">
                            <span class="font-bold">Équipe 1</span>
                        </td>
                        <td class="p-3 text-center font-bold">0</td>
                        <td class="p-3 text-center">0</td>
                        <td class="p-3 text-center">0</td>
                        <td class="p-3 text-center">0</td>
                        <td class="p-3 text-center">0</td>
                        <td class="p-3 text-center">0</td>
                    </tr>

                    <!-- Répétez les lignes pour chaque équipe -->
                    <tr class="bg-gray-100">
                        <td class="p-3 text-center font-bold text-red-500">02</td>
                        <td class="p-3 flex items-center">
                            <img src="path-to-logo" alt="Logo" class="h-8 w-8 mr-3">
                            <span class="font-bold">Équipe 2</span>
                        </td>
                        <td class="p-3 text-center font-bold">0</td>
                        <td class="p-3 text-center">0</td>
                        <td class="p-3 text-center">0</td>
                        <td class="p-3 text-center">0</td>
                        <td class="p-3 text-center">0</td>
                        <td class="p-3 text-center">0</td>
                    </tr>
                    <!-- Ajoutez plus de lignes ici pour les autres équipes -->

                    <tr class="bg-gray-100">
                        <td class="p-3 text-center font-bold text-red-500">02</td>
                        <td class="p-3 flex items-center">
                            <img src="path-to-logo" alt="Logo" class="h-8 w-8 mr-3">
                            <span class="font-bold">Équipe 2</span>
                        </td>
                        <td class="p-3 text-center font-bold">0</td>
                        <td class="p-3 text-center">0</td>
                        <td class="p-3 text-center">0</td>
                        <td class="p-3 text-center">0</td>
                        <td class="p-3 text-center">0</td>
                        <td class="p-3 text-center">0</td>
                    </tr>

                    <tr class="bg-gray-100">
                        <td class="p-3 text-center font-bold text-red-500">02</td>
                        <td class="p-3 flex items-center">
                            <img src="path-to-logo" alt="Logo" class="h-8 w-8 mr-3">
                            <span class="font-bold">Équipe 2</span>
                        </td>
                        <td class="p-3 text-center font-bold">0</td>
                        <td class="p-3 text-center">0</td>
                        <td class="p-3 text-center">0</td>
                        <td class="p-3 text-center">0</td>
                        <td class="p-3 text-center">0</td>
                        <td class="p-3 text-center">0</td>
                    </tr>

                    <tr class="bg-gray-100">
                        <td class="p-3 text-center font-bold text-red-500">02</td>
                        <td class="p-3 flex items-center">
                            <img src="path-to-logo" alt="Logo" class="h-8 w-8 mr-3">
                            <span class="font-bold">Équipe 2</span>
                        </td>
                        <td class="p-3 text-center font-bold">0</td>
                        <td class="p-3 text-center">0</td>
                        <td class="p-3 text-center">0</td>
                        <td class="p-3 text-center">0</td>
                        <td class="p-3 text-center">0</td>
                        <td class="p-3 text-center">0</td>
                    </tr>


                    <tr class="bg-gray-100">
                        <td class="p-3 text-center font-bold text-red-500">02</td>
                        <td class="p-3 flex items-center">
                            <img src="path-to-logo" alt="Logo" class="h-8 w-8 mr-3">
                            <span class="font-bold">Équipe 2</span>
                        </td>
                        <td class="p-3 text-center font-bold">0</td>
                        <td class="p-3 text-center">0</td>
                        <td class="p-3 text-center">0</td>
                        <td class="p-3 text-center">0</td>
                        <td class="p-3 text-center">0</td>
                        <td class="p-3 text-center">0</td>
                    </tr>

                    <tr class="bg-gray-100">
                        <td class="p-3 text-center font-bold text-red-500">02</td>
                        <td class="p-3 flex items-center">
                            <img src="path-to-logo" alt="Logo" class="h-8 w-8 mr-3">
                            <span class="font-bold">Équipe 2</span>
                        </td>
                        <td class="p-3 text-center font-bold">0</td>
                        <td class="p-3 text-center">0</td>
                        <td class="p-3 text-center">0</td>
                        <td class="p-3 text-center">0</td>
                        <td class="p-3 text-center">0</td>
                        <td class="p-3 text-center">0</td>
                    </tr>

                    <tr class="bg-gray-100">
                        <td class="p-3 text-center font-bold text-red-500">02</td>
                        <td class="p-3 flex items-center">
                            <img src="path-to-logo" alt="Logo" class="h-8 w-8 mr-3">
                            <span class="font-bold">Équipe 2</span>
                        </td>
                        <td class="p-3 text-center font-bold">0</td>
                        <td class="p-3 text-center">0</td>
                        <td class="p-3 text-center">0</td>
                        <td class="p-3 text-center">0</td>
                        <td class="p-3 text-center">0</td>
                        <td class="p-3 text-center">0</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </main>

    <x-footer />
</body>
</html>

