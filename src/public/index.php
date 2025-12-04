<?php

require_once __DIR__ . '/bootstrap.php';

$pdoMessage = 'not connected';
try {
    $pdo = createPdo();
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
</head>
<body>
    <h1>PHP 8.3 with Nginx</h1>
    <p>MySQL status: <?php echo escape($pdoMessage); ?></p>
    <p>PHP version: <?php echo phpversion(); ?></p>
    <p><a href="/highscore/">View minigame highscores</a></p>
</body>
</html>
