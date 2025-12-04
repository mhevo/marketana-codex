<?php

require_once __DIR__ . '/bootstrap.php';

$pdoMessage = 'not connected';
try {
    $pdo = createPdo();
    // Use a neutral alias to avoid conflicts with reserved keywords across MySQL versions
    $stmt = $pdo->query('SELECT NOW() as current_time_value');
    $time = $stmt->fetch(PDO::FETCH_ASSOC)['current_time_value'] ?? 'unknown';
    $pdoMessage = 'connected (time: ' . $time . ')';
} catch (Throwable $e) {
    $pdoMessage = 'connection failed: ' . $e->getMessage();
}
?>
<!doctype html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marketing-Spiel: KI-Texte liefern</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=Source+Serif+4:wght@400;600&display=swap');

        :root {
            --bg: #f7f2ed;
            --ink: #2c2742;
            --muted: #6b5d7a;
            --accent: #b18acb;
            --accent-2: #8ac6d1;
            --card: rgba(255, 255, 255, 0.9);
            --border: rgba(44, 39, 66, 0.12);
            --shadow: 0 25px 80px rgba(44, 39, 66, 0.14);
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            background: radial-gradient(circle at 15% 20%, rgba(177, 138, 203, 0.12), transparent 25%),
                        radial-gradient(circle at 80% 10%, rgba(138, 198, 209, 0.12), transparent 25%),
                        linear-gradient(135deg, #fdfbf8, var(--bg));
            color: var(--ink);
            font-family: 'Source Serif 4', 'Georgia', serif;
            padding: 32px 20px 40px;
        }

        .page {
            max-width: 1080px;
            margin: 0 auto;
        }

        nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
            padding: 14px 18px;
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 16px;
            box-shadow: var(--shadow);
        }

        .brand {
            font-family: 'Playfair Display', 'Georgia', serif;
            font-size: 1.4rem;
            letter-spacing: 0.04em;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .brand span {
            padding: 8px 12px;
            border-radius: 12px;
            background: linear-gradient(120deg, rgba(177, 138, 203, 0.15), rgba(138, 198, 209, 0.18));
            border: 1px solid var(--border);
        }

        .nav-links {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .nav-links a {
            color: var(--ink);
            text-decoration: none;
            padding: 10px 14px;
            border-radius: 12px;
            border: 1px solid transparent;
            transition: all 0.2s ease;
            font-weight: 600;
        }

        .nav-links a:hover {
            border-color: var(--border);
            background: rgba(177, 138, 203, 0.08);
        }

        main {
            margin-top: 20px;
            display: grid;
            grid-template-columns: 1fr;
            gap: 16px;
        }

        .card {
            background: var(--card);
            border-radius: 18px;
            padding: 24px;
            border: 1px solid var(--border);
            box-shadow: var(--shadow);
        }

        h1 {
            font-family: 'Playfair Display', 'Georgia', serif;
            margin: 0 0 12px;
            font-size: 2rem;
        }

        p {
            margin: 0 0 12px;
            line-height: 1.7;
            color: var(--muted);
        }

        .pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(138, 198, 209, 0.16);
            border: 1px solid rgba(138, 198, 209, 0.35);
            color: var(--ink);
            padding: 8px 12px;
            border-radius: 999px;
            font-weight: 700;
        }

        .steps {
            list-style: none;
            padding: 0;
            margin: 16px 0 0;
            display: grid;
            gap: 10px;
        }

        .steps li {
            padding: 12px 14px;
            border: 1px solid var(--border);
            border-radius: 14px;
            background: linear-gradient(120deg, rgba(177, 138, 203, 0.06), rgba(138, 198, 209, 0.08));
            color: var(--ink);
            font-weight: 600;
        }

        .cta {
            margin-top: 18px;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 12px 18px;
            background: linear-gradient(120deg, var(--accent), var(--accent-2));
            color: #0f0c1f;
            font-weight: 800;
            text-decoration: none;
            border-radius: 14px;
            border: 1px solid rgba(44, 39, 66, 0.18);
            box-shadow: 0 15px 40px rgba(44, 39, 66, 0.2);
            transition: transform 0.15s ease, box-shadow 0.2s ease;
        }

        .cta:hover {
            transform: translateY(-1px);
            box-shadow: 0 20px 50px rgba(44, 39, 66, 0.28);
        }

        footer {
            margin-top: 14px;
            color: var(--muted);
            font-size: 0.95rem;
        }

        @media (max-width: 720px) {
            nav, .card { padding: 18px; }
        }
    </style>
</head>
<body>
    <div class="page">
        <nav>
            <div class="brand">
                <span>Bridgerton Society</span>
                <strong>Marketing-Salon</strong>
            </div>
            <div class="nav-links">
                <a href="/game/">Zum Spiel</a>
                <a href="/highscore/">Highscores</a>
            </div>
        </nav>

        <main>
            <section class="card">
                <div class="pill">🚀 Marketing-Spiel</div>
                <h1>Schreibe mehr KI-Texte als alle anderen</h1>
                <p>
                    Willkommen im Salon der Marketing-Strateg:innen! Lass die KI für dich arbeiten, präsentiere
                    deiner Leitung funkelnde Ideen und sichere dir Ruhm sowie Belohnungen.
                </p>
                <ul class="steps">
                    <li><strong>1.</strong> Formuliere ein Briefing und lass die KI die Texte erstellen.</li>
                    <li><strong>2.</strong> Präsentiere die Ergebnisse deinem Chef und sammle seine Anerkennung.</li>
                    <li><strong>3.</strong> Wiederhole den Prozess und sichere dir immer höhere Entlohnungen.</li>
                </ul>
                <a class="cta" href="/game/">Zum Spiel starten 🚀</a>
                <footer>
                    <strong>Systemstatus:</strong> MySQL ist <?php echo htmlspecialchars($pdoMessage, ENT_QUOTES, 'UTF-8'); ?>.
                </footer>
            </section>
        </main>
    </div>
</body>
</html>
