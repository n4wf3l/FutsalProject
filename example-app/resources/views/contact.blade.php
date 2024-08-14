<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
     integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
     <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
     integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
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

        .contact-info .info-item img {
            margin-right: 1rem;
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

        #map {
            width: 100%;
            height: 400px;
            margin-top: 20px;
            border-radius: 0.5rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            position: relative;
            z-index: 2;
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
            <h2>Contacteer ons</h2>
            <h1>Kom in contact</h1>
            <p>Vul het formulier in en wij nemen zo snel mogelijk contact met u op ivm uw vraag of opmerking.</p>

            <div class="info-item">
                <img src="path-to-location-icon" alt="Location Icon" class="h-6 w-6">
                <div>
                    <strong>Adres</strong>
                    <p>Sportcomplex Het Rooi<br>Berchemstadionstraat</p>
                </div>
            </div>

            <div class="info-item">
                <img src="path-to-phone-icon" alt="Phone Icon" class="h-6 w-6">
                <div>
                    <strong>Telefoonnummer</strong>
                    <p>GC: +32 488 87 34 00</p>
                </div>
            </div>

            <div class="info-item">
                <img src="path-to-email-icon" alt="Email Icon" class="h-6 w-6">
                <div>
                    <strong>Emailadres</strong>
                    <p>info@ftantwerpen.be</p>
                </div>
            </div>
        </div>

        <div class="contact-form">
            <form>
                <div class="form-group">
                    <label for="firstname">Voornaam *</label>
                    <input type="text" id="firstname" required>
                </div>
                <div class="form-group">
                    <label for="lastname">Achternaam *</label>
                    <input type="text" id="lastname" required>
                </div>
                <div class="form-group">
                    <label for="email">Emailadres *</label>
                    <input type="email" id="email" required>
                </div>
                <div class="form-group">
                    <label for="phone">Telefoonnummer *</label>
                    <input type="tel" id="phone" required>
                </div>
                <div class="form-group">
                    <label for="message">Opmerking of Vragen *</label>
                    <textarea id="message" required></textarea>
                </div>
                <button type="submit" class="submit-button">Versturen</button>
            </form>
        </div>
    </div>

    <div id="map"></div>

    <x-footer />

    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        var map = L.map('map').setView([51.505, -0.09], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        L.marker([51.505, -0.09]).addTo(map)
            .bindPopup('A pretty CSS popup.<br> Easily customizable.')
            .openPopup();
    </script>
    
</body>

</html>
