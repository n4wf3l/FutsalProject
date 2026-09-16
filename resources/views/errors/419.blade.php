@php
    $loginUrl = url('/login');
    $homeUrl = url('/');
@endphp
<!DOCTYPE html>
<html lang="fr" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="robots" content="noindex, nofollow">
        <title>Session expirée, Dina Kenitra FC</title>
        <link rel="icon" type="image/png" href="/logo-dinakenitra.png">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link
            rel="stylesheet"
            href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Space+Grotesk:wght@600;700&family=Fraunces:ital,wght@1,500&display=swap"
        >
        <style>
            :root {
                --bg: #0B0C10;
                --card: #14151B;
                --border: #23252E;
                --foreground: #E9E3D5;
                --muted: #94897A;
                --crimson: #E64A52;
                --champagne: #DFBE7C;
            }
            * { box-sizing: border-box; }
            html, body {
                margin: 0;
                padding: 0;
                min-height: 100vh;
                background: var(--bg);
                color: var(--foreground);
                font-family: 'Inter', system-ui, -apple-system, sans-serif;
                -webkit-font-smoothing: antialiased;
            }
            main {
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 32px 20px;
            }
            .card {
                width: 100%;
                max-width: 560px;
                border: 1px solid var(--border);
                background: var(--card);
                border-radius: 20px;
                padding: 44px 40px;
            }
            .kicker {
                font-family: 'Space Grotesk', 'Inter', sans-serif;
                font-size: 11px;
                font-weight: 700;
                letter-spacing: 0.3em;
                text-transform: uppercase;
                color: var(--champagne);
                display: flex;
                align-items: center;
                gap: 12px;
                margin-bottom: 20px;
            }
            .kicker::before {
                content: '';
                display: block;
                width: 32px;
                height: 1px;
                background: var(--champagne);
            }
            h1 {
                font-family: 'Space Grotesk', 'Inter', sans-serif;
                font-size: 40px;
                line-height: 1.05;
                font-weight: 700;
                margin: 0 0 4px 0;
            }
            .sub {
                font-family: 'Fraunces', 'Inter', serif;
                font-style: italic;
                font-weight: 500;
                font-size: 28px;
                color: var(--champagne);
                margin-bottom: 22px;
            }
            p {
                color: var(--muted);
                line-height: 1.65;
                margin: 0 0 14px 0;
            }
            p strong { color: var(--foreground); font-weight: 600; }
            .actions {
                display: flex;
                flex-wrap: wrap;
                gap: 12px;
                margin-top: 28px;
            }
            .btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
                padding: 12px 20px;
                border-radius: 12px;
                font-size: 14px;
                font-weight: 600;
                text-decoration: none;
                border: 1px solid transparent;
                transition: background-color 0.15s ease, border-color 0.15s ease, color 0.15s ease;
                cursor: pointer;
                font-family: inherit;
            }
            .btn-primary {
                background: var(--crimson);
                color: #fff;
            }
            .btn-primary:hover { background: #d43a42; }
            .btn-outline {
                background: transparent;
                color: var(--foreground);
                border-color: var(--border);
            }
            .btn-outline:hover { border-color: var(--champagne); color: var(--champagne); }
            .code {
                margin-top: 32px;
                padding-top: 20px;
                border-top: 1px dashed var(--border);
                font-family: 'JetBrains Mono', ui-monospace, monospace;
                font-size: 11px;
                letter-spacing: 0.15em;
                text-transform: uppercase;
                color: var(--muted);
            }
            @media (max-width: 480px) {
                h1 { font-size: 32px; }
                .sub { font-size: 22px; }
                .card { padding: 32px 24px; }
            }
        </style>
    </head>
    <body>
        <main>
            <section class="card" role="alert" aria-live="polite">
                <div class="kicker">Session expirée</div>
                <h1>Ta session a expiré.</h1>
                <div class="sub">Reconnecte-toi pour continuer.</div>
                <p>
                    Pour ta sécurité, la session admin se termine après 2 heures d'inactivité. Le formulaire que tu essayais d'envoyer a été refusé parce que le jeton de sécurité n'était plus valide.
                </p>
                <p>
                    <strong>Ce que tu peux faire :</strong> reconnecte-toi, puis reviens à l'écran d'édition. Si tu avais un brouillon d'article ou d'interview ouvert dans un autre onglet, garde-le ouvert et copie ton texte avant de recharger.
                </p>
                <div class="actions">
                    <a class="btn btn-primary" href="{{ $loginUrl }}">Se reconnecter</a>
                    <a class="btn btn-outline" href="{{ $homeUrl }}">Retour à l'accueil</a>
                </div>
                <div class="code">Erreur 419 · CSRF token mismatch</div>
            </section>
        </main>
    </body>
</html>
