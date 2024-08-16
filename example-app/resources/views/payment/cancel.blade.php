<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Canceled</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @vite('resources/css/app.css')
</head>
<body>
    <div class="container mx-auto py-12">
        <h1 class="text-3xl font-bold text-red-600">Payment Canceled</h1>
        <p class="mt-4">Your payment was canceled. If you have any questions, please contact support.</p>
        <a href="{{ route('fanshop.index') }}" class="mt-6 inline-block text-white bg-red-500 py-2 px-4 rounded">Return to Fanshop</a>
    </div>
</body>
</html>
