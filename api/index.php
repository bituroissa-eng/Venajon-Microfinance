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

$vercelUrl = isset($_SERVER['VERCEL_URL']) ? $_SERVER['VERCEL_URL'] : (getenv('VERCEL_URL') ?: '');
if ($vercelUrl) {
    $_ENV['APP_URL'] = 'https://' . $vercelUrl;
    $_SERVER['APP_URL'] = 'https://' . $vercelUrl;
    $_ENV['ASSET_URL'] = 'https://' . $vercelUrl;
    $_SERVER['ASSET_URL'] = 'https://' . $vercelUrl;
    $_ENV['APP_ENV'] = 'production';
    $_SERVER['APP_ENV'] = 'production';
}

require __DIR__ . '/../public/index.php';
