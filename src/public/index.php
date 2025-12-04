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
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marketing-Spiel: KI-Texte liefern</title>
    <style>
        :root {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #0f172a;
            background-color: #f8fafc;
        }
        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        .card {
            max-width: 720px;
            width: 100%;
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(15, 23, 42, 0.15);
            padding: 2.5rem;
        }
        h1 {
            margin-top: 0;
            font-size: 2rem;
            color: #0ea5e9;
        }
        p {
            line-height: 1.6;
            margin: 0 0 1rem;
        }
        .steps {
            padding: 0;
            list-style: none;
            display: grid;
            gap: 0.75rem;
        }
        .steps li {
            background: #f1f5f9;
            border-radius: 12px;
            padding: 0.9rem 1rem;
            border: 1px solid #e2e8f0;
        }
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            background: #e0f2fe;
            color: #0369a1;
            padding: 0.4rem 0.8rem;
            border-radius: 999px;
            font-size: 0.9rem;
            font-weight: 600;
        }
        footer {
            margin-top: 2rem;
            font-size: 0.9rem;
            color: #475569;
        }
    </style>
</head>
<body>
    <main class="card">
        <div class="badge">🚀 Marketing-Spiel</div>
        <h1>Schreibe mehr KI-Texte als alle anderen</h1>
        <p>
            In diesem kleinen Marketing-Spiel geht es darum, die KI für dich arbeiten zu lassen:
            Lass sie so viele überzeugende Texte wie möglich erstellen, liefere sie deinem Chef
            und kassiere dafür die Belohnung. Je schneller du neue Ideen ablieferst, desto höher
            steigt dein Wert.
        </p>
        <ul class="steps">
            <li><strong>1.</strong> Formuliere ein Briefing und lass die KI die Texte erstellen.</li>
            <li><strong>2.</strong> Präsentiere die Ergebnisse deinem Chef und sammle seine Anerkennung.</li>
            <li><strong>3.</strong> Wiederhole den Prozess und sichere dir immer höhere Entlohnungen.</li>
        </ul>
        <footer>
            <strong>Systemstatus:</strong> MySQL ist <?php echo htmlspecialchars($pdoMessage, ENT_QUOTES, 'UTF-8'); ?>.
        </footer>
    </main>
</body>
</html>
