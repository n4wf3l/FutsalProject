<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendar | {{ $clubName }}</title>
    @if($logoPath)
        <link rel="icon" href="{{ $logoPath }}" type="image/png"> <!-- Type de l'image selon le type du logo -->
    @endif
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @vite('resources/css/app.css')
    <style>
        body {
            background-color: #f3f4f6;
        }
        .container {
            margin: 0 auto;
        }
        .table-container {
            display: flex;
            justify-content: center;
            max-width: 1000px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }
        th {
          background-color: {{ $primaryColor }};
            color: white;
        }
        tbody tr:nth-child(even) {
            background-color: #f9fafb;
        }
        tbody tr:nth-child(odd) {
            background-color: #ffffff;
        }
        .club-name {
            display: flex;
            align-items: center;
        }
        .club-logo {
            height: 24px;
            margin-right: 10px;
        }
        .calendar-title {
            font-size: 2rem;
            margin-top: 4rem;
            text-align: center;
            color: {{ $primaryColor }};
            font-weight: bold;
        }
    </style>
</head>
<body class="bg-gray-100">
  <x-navbar />
  <header class="text-center my-12" style="margin-top: 20px;">
        <h1 class="text-6xl font-bold text-gray-900" style="font-size:60px;">Classement Futsal D1</h1>
        <div class="flex justify-center items-center mt-4">
            <p class="text-xl text-gray-600" style="margin-bottom: 20px;">Discover additional information by hovering with your mouse.</p>
        </div>
    </header>

    <main class="py-12">
    <h2 class="calendar-title">Classement - Dina Kénitra</h2>
        <div class="container table-container">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Club</th>
                        <th>Pts</th>
                        <th>Jo</th>
                        <th>G</th>
                        <th>N</th>
                        <th>P</th>
                        <th>Diff</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Classement des clubs -->
                    <!-- Ajoutez les clubs ici -->
                    <tr>
                        <td>01</td>
                        <td class="club-name"><span>SCC Mohammédia (SCCM)</span></td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                    </tr>
                    <tr>
                        <td>02</td>
                        <td class="club-name"><span>Loukkous Ksar El Kebir (CLKK)</span></td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                    </tr>
                    <tr>
                        <td>03</td>
                        <td class="club-name"><span>Dynamo Kenitra (ACDK)</span></td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                    </tr>
                    <tr>
                        <td>04</td>
                        <td class="club-name"><span>CFS Settat (CFSS)</span></td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                    </tr>
                    <tr>
                        <td>05</td>
                        <td class="club-name"><span>AS Faucon Agadir (ASFA)</span></td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                    </tr>
                    <tr>
                        <td>06</td>
                        <td class="club-name"><span>Oussoud Khabazat (AOKHK)</span></td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                    </tr>
                    <tr>
                        <td>07</td>
                        <td class="club-name"><span>CJ Khouribga (CJK)</span></td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                    </tr>
                    <tr>
                        <td>08</td>
                        <td class="club-name"><span>Raja CA (RCA)</span></td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                    </tr>
                    <tr>
                        <td>09</td>
                        <td class="club-name"><span>Ajax Tetouan (FCAAT)</span></td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                    </tr>
                    <tr>
                        <td>10</td>
                        <td class="club-name"><span>Amal Tit Mellil (ASA2)</span></td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                    </tr>
                    <tr>
                        <td>11</td>
                        <td class="club-name"><span>Raja Agadir (RCMA)</span></td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                    </tr>
                    <tr>
                        <td>12</td>
                        <td class="club-name"><span>Olympique Tétouan (AOT)</span></td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                    </tr>
                    <tr>
                        <td>13</td>
                        <td class="club-name"><span>Dina Kénitra (DKFC)</span></td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                    </tr>
                    <tr>
                        <td>14</td>
                        <td class="club-name"><span>AS Martil (ASM)</span></td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                    </tr>
                    <tr>
                        <td>15</td>
                        <td class="club-name"><span>Nasr Settat (ANSS)</span></td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                    </tr>
                    <tr>
                        <td>16</td>
                        <td class="club-name"><span>Lions de Chaouia (ALC)</span></td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <h2 class="calendar-title">Calendrier des Matchs - Dina Kénitra</h2>
        <div class="container table-container">
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Heure</th>
                        <th>Domicile</th>
                        <th>Extérieur</th>
                        <th>Score</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Exemple de calendrier des matchs -->
                    <tr>
                        <td>20/08/2024</td>
                        <td>18:00</td>
                        <td>Dina Kénitra</td>
                        <td>Raja CA</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td>27/08/2024</td>
                        <td>18:00</td>
                        <td>CJ Khouribga</td>
                        <td>Dina Kénitra</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td>03/09/2024</td>
                        <td>18:00</td>
                        <td>Dina Kénitra</td>
                        <td>AS Faucon Agadir</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td>10/09/2024</td>
                        <td>18:00</td>
                        <td>Loukkous Ksar El Kebir</td>
                        <td>Dina Kénitra</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td>17/09/2024</td>
                        <td>18:00</td>
                        <td>Dina Kénitra</td>
                        <td>CFS Settat</td>
                        <td>-</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </main>

    <x-footer />
</body>
</html>
