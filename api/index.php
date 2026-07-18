<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$isVercel = isset($_ENV['VERCEL']) || getenv('VERCEL') || isset($_SERVER['VERCEL_URL']) || str_starts_with(__DIR__, '/var/task');
$storagePath = '/tmp/storage';

if (!$isVercel) {
    $storagePath = __DIR__ . '/../storage';
}

$directories = [
    $storagePath . '/app/public',
    $storagePath . '/framework/cache/data',
    $storagePath . '/framework/sessions',
    $storagePath . '/framework/testing',
    $storagePath . '/framework/views',
    $storagePath . '/logs',
    $storagePath . '/bootstrap/cache',
];

foreach ($directories as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
}

putenv("STORAGE_PATH={$storagePath}");
$_ENV['STORAGE_PATH'] = $storagePath;
$_SERVER['STORAGE_PATH'] = $storagePath;

if ($isVercel) {
    // Determine APP_URL
    $vercelUrl = getenv('VERCEL_URL') ?: ($_SERVER['HTTP_HOST'] ?? 'localhost');
    $appUrlEnv = getenv('APP_URL');

    if ($appUrlEnv && str_starts_with($appUrlEnv, 'http')) {
        $appUrl = rtrim($appUrlEnv, '/');
    } else {
        $host = preg_replace('#^https?://#', '', $vercelUrl);
        $host = trim($host, '/');
        $appUrl = 'https://' . $host;
    }

    // Fix SERVER vars for Laravel
    if (empty($_SERVER['HTTP_HOST'])) {
        $parsedUrl = parse_url($appUrl);
        $_SERVER['HTTP_HOST'] = $parsedUrl['host'] ?? 'localhost';
        $_SERVER['SERVER_NAME'] = $parsedUrl['host'] ?? 'localhost';
        $_SERVER['REQUEST_SCHEME'] = $parsedUrl['scheme'] ?? 'https';
        $_SERVER['HTTPS'] = 'on';
    }

    // Set fallback defaults only if not already set in Vercel env vars
    $defaults = [
        'APP_ENV'            => 'production',
        'APP_DEBUG'          => 'true',
        'APP_URL'            => $appUrl,
        'LOG_CHANNEL'        => 'stderr',
        'LOG_LEVEL'          => 'debug',
        'SESSION_DRIVER'     => 'cookie',
        'CACHE_STORE'        => 'array',
        'QUEUE_CONNECTION'   => 'sync',
        'FILESYSTEM_DISK'    => 'local',
        'VIEW_COMPILED_PATH' => $storagePath . '/framework/views',
        'APP_SERVICES_CACHE' => $storagePath . '/bootstrap/cache/services.php',
        'APP_PACKAGES_CACHE' => $storagePath . '/bootstrap/cache/packages.php',
        'APP_CONFIG_CACHE'   => $storagePath . '/bootstrap/cache/config.php',
        'APP_ROUTES_CACHE'   => $storagePath . '/bootstrap/cache/routes.php',
        'APP_EVENTS_CACHE'   => $storagePath . '/bootstrap/cache/events.php',
    ];

    foreach ($defaults as $key => $value) {
        $existing = getenv($key);
        if ($existing === false || $existing === '') {
            putenv("{$key}={$value}");
            $_ENV[$key] = $value;
            $_SERVER[$key] = $value;
        }
    }

    // Generate and persist APP_KEY if not set
    $existingKey = getenv('APP_KEY');
    if ($existingKey === false || $existingKey === '') {
        $appKeyFile = $storagePath . '/appkey';
        if (file_exists($appKeyFile)) {
            $appKey = trim(file_get_contents($appKeyFile));
        } else {
            $appKey = 'base64:' . base64_encode(random_bytes(32));
            file_put_contents($appKeyFile, $appKey);
        }
        putenv("APP_KEY={$appKey}");
        $_ENV['APP_KEY'] = $appKey;
        $_SERVER['APP_KEY'] = $appKey;
    }

    // Run migrations once (skipped if Supabase env vars already set and migrated)
    $migratedMarker = $storagePath . '/.migrated';
    $dbConnection = getenv('DB_CONNECTION');
    if (!file_exists($migratedMarker) && $dbConnection && $dbConnection !== '') {
        $phpBinary = PHP_BINARY ?: 'php';
        $artisan = escapeshellarg(__DIR__ . '/../artisan');
        $output = [];
        $status = 0;
        exec(escapeshellarg($phpBinary) . ' ' . $artisan . ' migrate --force 2>&1', $output, $status);
        if ($status === 0) {
            file_put_contents($migratedMarker, date('Y-m-d H:i:s'));
        } else {
            error_log('Artisan migrate failed: ' . implode("\n", $output));
        }
    }
}

require __DIR__ . '/../public/index.php';
