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

if ($isVercel) {
    $vercelUrl = getenv('VERCEL_URL') ?: ($_SERVER['HTTP_HOST'] ?? null);
    $appUrlEnv = getenv('APP_URL');

    if ($appUrlEnv && str_starts_with($appUrlEnv, 'http')) {
        $appUrl = rtrim($appUrlEnv, '/');
    } elseif ($vercelUrl) {
        $host = preg_replace('#^https?://#', '', $vercelUrl);
        $host = trim($host, '/');
        $appUrl = 'https://' . $host;
    } else {
        $host = $_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME'] ?? 'localhost';
        $host = trim($host);
        if ($host === '' || $host === ':' || $host === 'http:' || $host === 'https:') {
            $host = 'localhost';
        }
        $proto = $_SERVER['HTTP_X_FORWARDED_PROTO'] ?? $_SERVER['REQUEST_SCHEME'] ?? 'https';
        $proto = strtolower($proto) === 'http' ? 'http' : 'https';
        $appUrl = $proto . '://' . $host;
    }

    if (!isset($_SERVER['HTTP_HOST']) || empty($_SERVER['HTTP_HOST'])) {
        $parsedUrl = parse_url($appUrl);
        if ($parsedUrl !== false && isset($parsedUrl['host'])) {
            $_SERVER['HTTP_HOST'] = $parsedUrl['host'] . (isset($parsedUrl['port']) ? ':' . $parsedUrl['port'] : '');
            $_SERVER['SERVER_NAME'] = $parsedUrl['host'];
            $_SERVER['REQUEST_SCHEME'] = $parsedUrl['scheme'] ?? 'https';
            $_SERVER['HTTPS'] = ($parsedUrl['scheme'] ?? '') === 'https' ? 'on' : 'off';
        }
    }

    $runtimeDefaults = [
        'APP_ENV' => 'production',
        'APP_DEBUG' => 'true',
        'APP_URL' => $appUrl,
        'LOG_CHANNEL' => 'stderr',
        'LOG_LEVEL' => 'warning',
        'DB_CONNECTION' => 'sqlite',
        'DB_DATABASE' => $storagePath . '/database.sqlite',
        'SESSION_DRIVER' => 'cookie',
        'CACHE_STORE' => 'array',
        'QUEUE_CONNECTION' => 'sync',
        'FILESYSTEM_DISK' => 'local',
        'VIEW_COMPILED_PATH' => $storagePath . '/framework/views',
        'APP_CONFIG_CACHE' => $storagePath . '/config.php',
        'APP_ROUTES_CACHE' => $storagePath . '/routes.php',
        'APP_EVENTS_CACHE' => $storagePath . '/events.php',
        'APP_SERVICES_CACHE' => $storagePath . '/services.php',
    ];

    foreach ($runtimeDefaults as $key => $value) {
        $existing = getenv($key);
        if ($existing === false || $existing === '') {
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

    $existingAppKey = getenv('APP_KEY');
    if ($existingAppKey === false || $existingAppKey === '') {
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
        $artisan = escapeshellarg(__DIR__ . '/../artisan');
        $phpBinary = defined('PHP_BINARY') ? PHP_BINARY : 'php';

        exec(escapeshellarg($phpBinary) . ' ' . $artisan . ' migrate --force 2>&1', $output, $status);
        if ($status === 0) {
            exec(escapeshellarg($phpBinary) . ' ' . $artisan . ' db:seed --force 2>&1', $output, $status);
            file_put_contents($migratedMarker, 'migrated');
        } else {
            error_log('Artisan migrate failed: ' . implode("\n", $output));
        }
    }
}

require __DIR__ . '/../public/index.php';
