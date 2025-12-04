<?php

$mysqlHost = getenv('MYSQL_HOST') ?: 'db';
$mysqlDb = getenv('MYSQL_DATABASE') ?: 'app_db';
$mysqlUser = getenv('MYSQL_USER') ?: 'app_user';
$mysqlPass = getenv('MYSQL_PASSWORD') ?: 'app_password';

$connectionError = null;
$highscores = [];

function escape(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

try {
    $dsn = sprintf('mysql:host=%s;dbname=%s;charset=utf8mb4', $mysqlHost, $mysqlDb);
    $pdo = new PDO($dsn, $mysqlUser, $mysqlPass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minigame Highscores</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 2rem auto;
            max-width: 800px;
            padding: 0 1rem;
            line-height: 1.6;
            color: #1f2937;
            background-color: #f9fafb;
        }
        h1 {
            color: #111827;
            margin-bottom: 0.5rem;
        }
        .lead {
            margin-bottom: 1.5rem;
            color: #4b5563;
        }
        .error {
            color: #b91c1c;
            background: #fee2e2;
            border: 1px solid #fecaca;
            padding: 0.75rem 1rem;
            border-radius: 8px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }
        th, td {
            padding: 0.75rem 1rem;
            text-align: left;
        }
        th {
            background: #f3f4f6;
            font-weight: 600;
            color: #374151;
            border-bottom: 1px solid #e5e7eb;
        }
        tr + tr td {
            border-top: 1px solid #e5e7eb;
        }
        .empty-state {
            margin-top: 1rem;
            padding: 1rem 1.25rem;
            background: #eef2ff;
            border: 1px solid #e0e7ff;
            color: #4338ca;
            border-radius: 8px;
        }
        .meta {
            margin-top: 0.75rem;
            color: #6b7280;
        }
        a {
            color: #2563eb;
        }
    </style>
</head>
<body>
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
</body>
</html>
