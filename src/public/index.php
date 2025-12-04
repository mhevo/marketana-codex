<?php

$mysqlHost = getenv('MYSQL_HOST') ?: 'db';
$mysqlDb = getenv('MYSQL_DATABASE') ?: 'app_db';
$mysqlUser = getenv('MYSQL_USER') ?: 'app_user';
$mysqlPass = getenv('MYSQL_PASSWORD') ?: 'app_password';

$pdoMessage = 'not connected';
try {
    $dsn = sprintf('mysql:host=%s;dbname=%s;charset=utf8mb4', $mysqlHost, $mysqlDb);
    $pdo = new PDO($dsn, $mysqlUser, $mysqlPass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    $stmt = $pdo->query('SELECT NOW() as current_time');
    $time = $stmt->fetch(PDO::FETCH_ASSOC)['current_time'] ?? 'unknown';
    $pdoMessage = 'connected (time: ' . $time . ')';
} catch (Throwable $e) {
    $pdoMessage = 'connection failed: ' . $e->getMessage();
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP 8.3 + Nginx + MySQL</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #0f172a;
            color: #e5e7eb;
            margin: 0;
            padding: 40px 20px;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            background: rgba(17, 24, 39, 0.92);
            border: 1px solid rgba(148, 163, 184, 0.15);
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
        }
        h1 { margin-top: 0; }
        a {
            color: #38bdf8;
            text-decoration: none;
            font-weight: bold;
        }
        a:hover { text-decoration: underline; }
        .status {
            padding: 12px 16px;
            background: rgba(148, 163, 184, 0.12);
            border: 1px solid rgba(148, 163, 184, 0.2);
            border-radius: 12px;
            margin: 10px 0;
        }
        .cta {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            margin-top: 12px;
            padding: 12px 14px;
            background: linear-gradient(135deg, #38bdf8, #0ea5e9);
            color: #0b1222;
            border-radius: 12px;
            font-weight: 800;
            box-shadow: 0 12px 30px rgba(56, 189, 248, 0.35);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>PHP 8.3 with Nginx</h1>
        <p>MySQL status: <?php echo htmlspecialchars($pdoMessage, ENT_QUOTES, 'UTF-8'); ?></p>
        <p>PHP version: <?php echo phpversion(); ?></p>
        <div class="status">Neue Spielwiese: Schreibe KI-Artikel und kassiere Honorare.</div>
        <a class="cta" href="/game">🚀 Zum Redaktions-Game</a>
    </div>
</body>
</html>
