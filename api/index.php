<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

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
    // The application source is read-only in Vercel runtime.
    // Do not copy .env at runtime. Environment variables should be configured in Vercel.
}

if ($isVercel) {
    $host = getenv('APP_URL') ?: (getenv('VERCEL_URL') ?: ($_SERVER['HTTP_HOST'] ?? null));
    $proto = getenv('HTTP_X_FORWARDED_PROTO') ?: getenv('REQUEST_SCHEME') ?: strtolower(($_SERVER['HTTPS'] ?? '') === 'on' ? 'https' : 'http');
    if (!$host) {
        $host = 'localhost';
    }
    $appUrl = str_starts_with($host, 'http') ? $host : ($proto . '://' . $host);

    $runtimeDefaults = [
        'APP_ENV' => 'production',
        'APP_DEBUG' => 'true',
        'APP_URL' => $appUrl,
        'LOG_LEVEL' => 'warning',
        'DB_CONNECTION' => 'sqlite',
        'DB_DATABASE' => $storagePath . '/database.sqlite',
        'SESSION_DRIVER' => 'file',
        'CACHE_STORE' => 'file',
        'QUEUE_CONNECTION' => 'sync',
        'FILESYSTEM_DISK' => 'local',
    ];

    foreach ($runtimeDefaults as $key => $value) {
        if (getenv($key) === false) {
            $_ENV[$key] = $value;
            $_SERVER[$key] = $value;
            putenv("{$key}={$value}");
        }
    }

    $appKeyFile = $storagePath . '/appkey';
    if (file_exists($appKeyFile)) {
        $appKey = trim(file_get_contents($appKeyFile));
    } else {
        $appKey = 'base64:' . base64_encode(random_bytes(32));
        file_put_contents($appKeyFile, $appKey);
    }

    if (!getenv('APP_KEY')) {
        $_ENV['APP_KEY'] = $appKey;
        $_SERVER['APP_KEY'] = $appKey;
        putenv("APP_KEY={$appKey}");
    }

    if ($_ENV['DB_CONNECTION'] === 'sqlite' && !file_exists($_ENV['DB_DATABASE'])) {
        touch($_ENV['DB_DATABASE']);
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
