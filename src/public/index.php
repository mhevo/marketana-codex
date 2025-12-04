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
</head>
<body>
    <h1>PHP 8.3 with Nginx</h1>
    <p>MySQL status: <?php echo htmlspecialchars($pdoMessage, ENT_QUOTES, 'UTF-8'); ?></p>
    <p>PHP version: <?php echo phpversion(); ?></p>
</body>
</html>
