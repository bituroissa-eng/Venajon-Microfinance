<?php
$projectRoot = dirname(__DIR__);
$isVercel = getenv('VERCEL') || getenv('VERCEL_URL');
$storagePath = getenv('STORAGE_PATH') ?: ($isVercel ? '/tmp/storage' : $projectRoot . '/storage');

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

$envPath = $projectRoot . '/.env';
$envExamplePath = $projectRoot . '/.env.example';

if (!file_exists($envPath) && file_exists($envExamplePath)) {
    copy($envExamplePath, $envPath);
}

if ($isVercel && file_exists($envPath)) {
    $envContents = file_get_contents($envPath);
    $cacheStore = getenv('CACHE_STORE');
    if ($cacheStore === false || $cacheStore === '' || $cacheStore === 'database') {
        $cacheStore = 'file';
    }

    $defaults = [
        'APP_ENV' => getenv('APP_ENV') ?: 'production',
        'APP_DEBUG' => getenv('APP_DEBUG') ?: 'false',
        'APP_URL' => getenv('APP_URL') ?: (getenv('VERCEL_URL') ? 'https://' . getenv('VERCEL_URL') : 'https://localhost'),
        'SESSION_DRIVER' => getenv('SESSION_DRIVER') ?: 'file',
        'CACHE_STORE' => $cacheStore,
        'QUEUE_CONNECTION' => getenv('QUEUE_CONNECTION') ?: 'sync',
    ];

    if (getenv('DATABASE_URL') || getenv('DB_URL')) {
        $defaults['DB_CONNECTION'] = 'pgsql';
    } elseif (getenv('DB_CONNECTION') && getenv('DB_CONNECTION') !== 'sqlite') {
        $defaults['DB_CONNECTION'] = getenv('DB_CONNECTION');
    } else {
        $defaults['DB_CONNECTION'] = 'sqlite';
    }

    if (getenv('DB_DATABASE')) {
        $defaults['DB_DATABASE'] = getenv('DB_DATABASE');
    } elseif (!getenv('DATABASE_URL') && !getenv('DB_URL')) {
        $defaults['DB_DATABASE'] = '/tmp/storage/database.sqlite';
    }

    foreach ($defaults as $key => $value) {
        $pattern = '/^' . preg_quote($key, '/') . '=.*$/m';
        if (preg_match($pattern, $envContents)) {
            $envContents = preg_replace($pattern, $key . '=' . $value, $envContents, 1);
        } else {
            $envContents .= PHP_EOL . $key . '=' . $value;
        }
    }

    file_put_contents($envPath, $envContents);
}

$composerInstaller = $projectRoot . '/composer-setup.php';
if (!file_exists($projectRoot . '/vendor/autoload.php')) {
    exec("php -r \"copy('https://getcomposer.org/installer', '{$composerInstaller}');\"");
    exec("php {$composerInstaller} --install-dir=/tmp --filename=composer");
    exec('php /tmp/composer install --no-dev --prefer-dist --ignore-platform-reqs');
}

$commands = [
    'php artisan key:generate --force',
    'php artisan storage:link --force',
    'php artisan migrate --force',
    'npm install',
    'npm run build',
];

foreach ($commands as $command) {
    echo "Running: {$command}\n";
    $output = [];
    $status = 0;
    exec($command, $output, $status);
    echo implode(PHP_EOL, $output) . PHP_EOL;
    if ($status !== 0) {
        fwrite(STDERR, "Command failed: {$command}\n");
        exit($status);
    }
}
