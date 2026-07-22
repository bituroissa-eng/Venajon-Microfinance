<?php

// On Vercel (and other serverless platforms), only /tmp is writable.
$storagePath = getenv('STORAGE_PATH') ?: '/tmp/storage';

$directories = [
    $storagePath . '/app/public',
    $storagePath . '/framework/cache/data',
    $storagePath . '/framework/sessions',
    $storagePath . '/framework/testing',
    $storagePath . '/framework/views',
    $storagePath . '/logs',
];

foreach ($directories as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
}

$_ENV['STORAGE_PATH'] = $storagePath;
$_SERVER['STORAGE_PATH'] = $storagePath;

// Force Vercel-compatible cache and session
$cacheStore = getenv('CACHE_STORE');
if ($cacheStore === 'y' || $cacheStore === 'database' || !$cacheStore) {
    $_ENV['CACHE_STORE'] = 'file';
    $_SERVER['CACHE_STORE'] = 'file';
    putenv('CACHE_STORE=file');
}

$_ENV['SESSION_DRIVER'] = 'cookie';
$_SERVER['SESSION_DRIVER'] = 'cookie';
putenv('SESSION_DRIVER=cookie');

// If using SQLite, copy the repo database to /tmp so it's readable/writable
$dbConnection = getenv('DB_CONNECTION') ?: 'sqlite';
if ($dbConnection === 'sqlite') {
    $dbDest = $storagePath . '/database.sqlite';
    if (!file_exists($dbDest)) {
        $dbSource = __DIR__ . '/../database/database.sqlite';
        if (file_exists($dbSource)) {
            copy($dbSource, $dbDest);
        } else {
            touch($dbDest);
        }
    }
    $_ENV['DB_DATABASE'] = $dbDest;
    $_SERVER['DB_DATABASE'] = $dbDest;
    putenv('DB_DATABASE=' . $dbDest);
}

// Load .env.example into environment if .env is missing (to provide fallbacks)
if (!file_exists(__DIR__ . '/../.env') && file_exists(__DIR__ . '/../.env.example')) {
    $envContent = file_get_contents(__DIR__ . '/../.env.example');
    foreach (explode("\n", $envContent) as $line) {
        $line = trim($line);
        if ($line && !str_starts_with($line, '#')) {
            $parts = explode('=', $line, 2);
            if (count($parts) === 2) {
                $key = $parts[0];
                $value = trim($parts[1], '"\'');
                if (!array_key_exists($key, $_SERVER) && !array_key_exists($key, $_ENV)) {
                    $_ENV[$key] = $value;
                    $_SERVER[$key] = $value;
                    putenv("$key=$value");
                }
            }
        }
    }
}

if (empty($_ENV['APP_KEY']) || !getenv('APP_KEY')) {
    $fallbackKey = 'base64:cYixN6dk2erzm5LPIYHa6d7ioNc5vGAeE6lIDL6xjLw=';
    $_ENV['APP_KEY'] = $fallbackKey;
    $_SERVER['APP_KEY'] = $fallbackKey;
    putenv('APP_KEY=' . $fallbackKey);
}

// Ensure Laravel bootstrap cache files don't cause read-only crashes
$_ENV['APP_SERVICES_CACHE'] = $storagePath . '/framework/cache/services.php';
$_SERVER['APP_SERVICES_CACHE'] = $storagePath . '/framework/cache/services.php';
putenv('APP_SERVICES_CACHE=' . $storagePath . '/framework/cache/services.php');

$_ENV['APP_PACKAGES_CACHE'] = $storagePath . '/framework/cache/packages.php';
$_SERVER['APP_PACKAGES_CACHE'] = $storagePath . '/framework/cache/packages.php';
putenv('APP_PACKAGES_CACHE=' . $storagePath . '/framework/cache/packages.php');

require __DIR__ . '/../public/index.php';
