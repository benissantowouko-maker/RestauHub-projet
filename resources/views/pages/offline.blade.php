<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hors ligne — RestauHub</title>
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#7F1D1D">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Instrument Sans', system-ui, sans-serif;
            background: #FAF7F2;
            min-height: 100dvh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            color: #1e293b;
        }
        .card {
            text-align: center;
            max-width: 380px;
            width: 100%;
        }
        .icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: #fef2f2;
            border: 3px solid #fecaca;
            margin-bottom: 1.5rem;
        }
        .icon svg { width: 36px; height: 36px; stroke: #991b1b; }
        h1 { font-size: 1.5rem; font-weight: 700; color: #1e293b; margin-bottom: 0.5rem; }
        p { font-size: 0.95rem; color: #64748b; line-height: 1.6; margin-bottom: 1.5rem; }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: #7F1D1D;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 999px;
            font-weight: 600;
            font-size: 0.9rem;
            text-decoration: none;
            transition: background 0.2s;
            border: none;
            cursor: pointer;
        }
        .btn:hover { background: #991b1b; }
        .tips {
            margin-top: 2rem;
            padding: 1rem;
            background: white;
            border-radius: 1rem;
            border: 1px solid #e2e8f0;
            text-align: left;
            font-size: 0.85rem;
            color: #475569;
        }
        .tips h2 { font-size: 0.875rem; font-weight: 700; color: #334155; margin-bottom: 0.5rem; }
        .tips ul { list-style: none; display: flex; flex-direction: column; gap: 0.35rem; }
        .tips li::before { content: "→ "; color: #7F1D1D; font-weight: bold; }
    </style>
</head>
<body>
    <div class="card">
        <div class="icon">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 5.636a9 9 0 010 12.728M5.636 5.636a9 9 0 000 12.728m4.243-9.9a4.5 4.5 0 010 6.364m2.121-3.182h.008v.008H12V12z"/>
            </svg>
        </div>

        <h1>Vous êtes hors ligne</h1>
        <p>Pas de connexion internet détectée. Vérifiez votre réseau Wi-Fi ou données mobiles puis réessayez.</p>

        <button class="btn" onclick="window.location.reload()">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7"/>
            </svg>
            Réessayer
        </button>

        <div class="tips">
            <h2>Conseils</h2>
            <ul>
                <li>Activez le Wi-Fi ou les données mobiles</li>
                <li>Vérifiez votre abonnement mobile</li>
                <li>Rapprochez-vous d'un point d'accès</li>
            </ul>
        </div>
    </div>

    <script>
        // Auto-reload when connection returns
        window.addEventListener('online', () => window.location.reload());
    </script>
</body>
</html>
