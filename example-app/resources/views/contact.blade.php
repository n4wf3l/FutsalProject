<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact | {{ $clubName }}</title>
    @if($logoPath)
        <link rel="icon" href="{{ $logoPath }}" type="image/png"> <!-- Type de l'image selon le type du logo -->
    @endif
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
     integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
     <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
     integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    @vite('resources/css/app.css')
    <style>
        .contact-container {
            display: flex;
            justify-content: space-between;
            max-width: 1200px;
            margin: 50px auto;
            padding: 2rem;
            background-color: #f9f9f9;
            border-radius: 0.5rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            position: relative;
            z-index: 2;
        }

        .contact-info {
            flex: 1;
            margin-right: 2rem;
        }

        .contact-info h2 {
            font-size: 1.5rem;
            font-weight: bold;
            color: {{ $primaryColor }};
            text-transform: uppercase;
            margin-bottom: 1rem;
        }

        .contact-info h1 {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 1rem;
            color: #333;
        }

        .contact-info p {
            font-size: 1.1rem;
            color: #555;
            margin-bottom: 2rem;
        }

        .contact-info .info-item {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .contact-info .info-item i {
            margin-right: 1rem;
            font-size: 1.5rem;
            color: {{ $primaryColor }};
        }

        .contact-form {
            flex: 1;
            background-color: #f2f2f2;
            padding: 2rem;
            border-radius: 0.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            font-weight: bold;
            color: {{ $primaryColor }};
            text-transform: uppercase;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ccc;
            border-radius: 0.5rem;
            margin-top: 0.5rem;
        }

        .form-group textarea {
            resize: none;
            height: 150px;
        }

        .submit-button {
            width: 100%;
            padding: 1rem;
            border-radius: 0.5rem;
            text-align: center;
            font-weight: bold;
            cursor: pointer;
            background-color:{{ $primaryColor }};
            color: white;
            transition: background-color 0.3s ease;
            text-transform: uppercase;
        }

        .submit-button:hover {
            background-color: {{ $secondaryColor }};
        }

        footer {
            position: relative;
            z-index: 1;
        }
    </style>
</head>

<body class="bg-gray-100">
    <x-navbar />
    <header class="text-center my-12" style="margin-top: 20px;">
        <h1 class="text-6xl font-bold text-gray-900" style="font-size:60px;">Contact</h1>
        <div class="flex justify-center items-center mt-4">
            <p class="text-xl text-gray-600" style="margin-bottom: 20px;">Discover additional information by hovering with your mouse.</p>
        </div>
    </header>

    <div class="contact-container">
        <div class="contact-info">
            <h2>Contact Us</h2>
            <h1>Get in Touch</h1>
            <p>Fill out the form and we will get in touch with you as soon as possible regarding your inquiry or feedback.</p>

            <div class="info-item">
                <i class="fas fa-map-marker-alt"></i>
                <div>
                    <strong>Address</strong>
                    <p>{{ $clubLocation }}</p>
                </div>
            </div>

            <div class="info-item">
                <i class="fas fa-phone-alt"></i>
                <div>
                    <strong>Phone Number</strong>
                    <p>GC: {{ $phone }}</p>
                </div>
            </div>

            <div class="info-item">
                <i class="fas fa-envelope"></i>
                <div>
                    <strong>Email Address</strong>
                    <p>{{ $email }}</p>
                </div>
            </div>
        </div>

        <div class="contact-form">
            <form>
                <div class="form-group">
                    <label for="firstname">First Name *</label>
                    <input type="text" id="firstname" required>
                </div>
                <div class="form-group">
                    <label for="lastname">Last Name *</label>
                    <input type="text" id="lastname" required>
                </div>
                <div class="form-group">
                    <label for="email">Email Address *</label>
                    <input type="email" id="email" required>
                </div>
                <div class="form-group">
                    <label for="phone">Phone Number *</label>
                    <input type="tel" id="phone" required>
                </div>
                <div class="form-group">
                    <label for="message">Message or Questions *</label>
                    <textarea id="message" required></textarea>
                </div>
                <button type="submit" class="submit-button">Send</button>
            </form>
        </div>
    </div>

    <x-footer />
</body>

</html>
