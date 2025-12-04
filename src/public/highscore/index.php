<?php

require_once __DIR__ . '/../bootstrap.php';

$connectionError = null;
$highscores = [];

try {
    $pdo = createPdo();

    $pdo->exec(
        'CREATE TABLE IF NOT EXISTS minigame_highscores (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            player_name VARCHAR(255) NOT NULL,
            score INT NOT NULL,
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_score_created_at (score DESC, created_at ASC)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4'
    );

    $stmt = $pdo->query('SELECT player_name, score, created_at FROM minigame_highscores ORDER BY score DESC, created_at ASC');
    $highscores = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Throwable $e) {
    $connectionError = $e->getMessage();
}
?>
<!doctype html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minigame Highscores</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=Source+Serif+4:wght@400;600&display=swap');

        :root {
            --bg: #f7f2ed;
            --ink: #2c2742;
            --muted: #6b5d7a;
            --accent: #b18acb;
            --accent-2: #8ac6d1;
            --card: rgba(255, 255, 255, 0.95);
            --border: rgba(44, 39, 66, 0.12);
            --shadow: 0 25px 80px rgba(44, 39, 66, 0.12);
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

        .page { max-width: 1080px; margin: 0 auto; }

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

        .nav-links { display: flex; gap: 12px; flex-wrap: wrap; }

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

        main { margin-top: 20px; }

        .card {
            background: var(--card);
            border-radius: 18px;
            padding: 22px;
            border: 1px solid var(--border);
            box-shadow: var(--shadow);
        }

        h1 { font-family: 'Playfair Display', 'Georgia', serif; margin: 0 0 10px; }
        p { margin: 0 0 12px; line-height: 1.7; color: var(--muted); }
        .lead { color: var(--ink); font-weight: 600; }

        .error {
            color: #872340;
            background: rgba(177, 138, 203, 0.14);
            border: 1px solid rgba(177, 138, 203, 0.3);
            padding: 0.75rem 1rem;
            border-radius: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        th, td { padding: 0.75rem 1rem; text-align: left; }

        th {
            background: linear-gradient(120deg, rgba(177, 138, 203, 0.14), rgba(138, 198, 209, 0.16));
            font-weight: 700;
            color: var(--ink);
            border-bottom: 1px solid #e5e7eb;
        }

        tr + tr td { border-top: 1px solid #e5e7eb; }

        .empty-state {
            margin-top: 1rem;
            padding: 1rem 1.25rem;
            background: rgba(138, 198, 209, 0.14);
            border: 1px solid rgba(138, 198, 209, 0.3);
            color: var(--ink);
            border-radius: 12px;
            font-weight: 600;
        }

        .meta { margin-top: 0.75rem; color: var(--muted); }

        a { color: var(--ink); }
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
                <a href="/">Startseite</a>
                <a href="/game/">Zum Spiel</a>
            </div>
        </nav>

        <main>
            <section class="card">
                <h1>Minigame Highscores</h1>
                <p class="lead">Alle Highscores, die über das Minigame erreicht wurden.</p>

                <?php if ($connectionError !== null): ?>
                    <p class="error">Datenbankfehler: <?php echo escape($connectionError); ?></p>
                <?php else: ?>
                    <?php if (empty($highscores)): ?>
                        <div class="empty-state">Noch keine Einträge vorhanden. Spiele das Minigame und sichere dir den ersten Platz!</div>
                    <?php else: ?>
                        <table aria-label="Highscore-Tabelle">
                            <thead>
                                <tr>
                                    <th scope="col">Rang</th>
                                    <th scope="col">Spieler</th>
                                    <th scope="col">Score</th>
                                    <th scope="col">Erreicht am</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($highscores as $index => $score): ?>
                                    <tr>
                                        <td><?php echo $index + 1; ?></td>
                                        <td><?php echo escape($score['player_name']); ?></td>
                                        <td><?php echo escape((string) $score['score']); ?></td>
                                        <td><?php echo escape($score['created_at']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <p class="meta">Sortiert nach Score (absteigend) und Datum (aufsteigend).</p>
                    <?php endif; ?>
                <?php endif; ?>

                <p class="meta"><a href="/">Zurück zur Startseite</a></p>
            </section>
        </main>
    </div>
</body>
</html>
