<?php

$isVercel = getenv('VERCEL') || getenv('VERCEL_URL');
$storagePath = getenv('STORAGE_PATH') ?: ($isVercel ? '/tmp/storage' : __DIR__ . '/../storage');

$directories = [
    $storagePath . '/app/public',
    $storagePath . '/framework/cache/data',
    $storagePath . '/framework/sessions',
    $storagePath . '/framework/testing',
    $storagePath . '/framework/views',
    $storagePath . '/logs',
];

foreach ($directories as $dir) {
    if (!is_dir($dir) && !mkdir($dir, 0777, true) && !is_dir($dir)) {
        throw new RuntimeException("Unable to create storage directory: {$dir}");
    }
}

$_ENV['STORAGE_PATH'] = $storagePath;
$_SERVER['STORAGE_PATH'] = $storagePath;

$envPath = __DIR__ . '/../.env';
$envExamplePath = __DIR__ . '/../.env.example';

if (!file_exists($envPath) && file_exists($envExamplePath)) {
    copy($envExamplePath, $envPath);
}

if ($isVercel) {
    $_ENV['APP_ENV'] = $_ENV['APP_ENV'] ?? 'production';
    $_ENV['APP_DEBUG'] = $_ENV['APP_DEBUG'] ?? 'false';
    $_ENV['APP_URL'] = $_ENV['APP_URL'] ?? (getenv('VERCEL_URL') ? 'https://' . getenv('VERCEL_URL') : 'https://localhost');
    $_ENV['LOG_LEVEL'] = $_ENV['LOG_LEVEL'] ?? 'warning';
    $_ENV['DB_CONNECTION'] = $_ENV['DB_CONNECTION'] ?? 'sqlite';
    $_ENV['DB_DATABASE'] = $_ENV['DB_DATABASE'] ?? $storagePath . '/database.sqlite';
    $_ENV['SESSION_DRIVER'] = $_ENV['SESSION_DRIVER'] ?? 'file';
    $_ENV['CACHE_STORE'] = $_ENV['CACHE_STORE'] ?? 'file';
    $_ENV['QUEUE_CONNECTION'] = $_ENV['QUEUE_CONNECTION'] ?? 'sync';
    $_ENV['FILESYSTEM_DISK'] = $_ENV['FILESYSTEM_DISK'] ?? 'local';

    if (!file_exists($_ENV['DB_DATABASE'])) {
        touch($_ENV['DB_DATABASE']);
    }

    if (file_exists($envPath)) {
        $envContents = file_get_contents($envPath);
        if (!preg_match('/^APP_KEY=.+/m', $envContents)) {
            exec('php ' . escapeshellarg(__DIR__ . '/../artisan') . ' key:generate --force');
        }
    }

    $migratedMarker = $storagePath . '/.migrated';
    if (!file_exists($migratedMarker)) {
        $output = [];
        $status = 0;
        exec('php ' . escapeshellarg(__DIR__ . '/../artisan') . ' migrate --force 2>&1', $output, $status);
        if ($status === 0) {
            file_put_contents($migratedMarker, 'migrated');
        }
    }
}

require __DIR__ . '/../public/index.php';
