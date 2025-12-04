<?php

declare(strict_types=1);

function getDbConfig(): array
{
    return [
        'host' => getenv('MYSQL_HOST') ?: 'db',
        'database' => getenv('MYSQL_DATABASE') ?: 'app_db',
        'user' => getenv('MYSQL_USER') ?: 'app_user',
        'password' => getenv('MYSQL_PASSWORD') ?: 'app_password',
    ];
}

function createPdo(): PDO
{
    $config = getDbConfig();
    $dsn = sprintf(
        'mysql:host=%s;dbname=%s;charset=utf8mb4',
        $config['host'],
        $config['database']
    );

    return new PDO($dsn, $config['user'], $config['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
}

function escape(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}
