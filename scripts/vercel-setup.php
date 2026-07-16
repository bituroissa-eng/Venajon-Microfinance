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
    if (!is_dir($dir) && !mkdir($dir, 0777, true) && !is_dir($dir)) {
        fwrite(STDERR, "Unable to create storage directory: {$dir}\n");
        exit(1);
    }
}

$_ENV['STORAGE_PATH'] = $storagePath;
$_SERVER['STORAGE_PATH'] = $storagePath;

$envPath = $projectRoot . '/.env';
$envExamplePath = $projectRoot . '/.env.example';

if (!file_exists($envPath) && file_exists($envExamplePath)) {
    copy($envExamplePath, $envPath);
}

$composerInstaller = $projectRoot . '/composer-setup.php';
if (!file_exists($projectRoot . '/vendor/autoload.php')) {
    exec("php -r \"copy('https://getcomposer.org/installer', '{$composerInstaller}');\"");
    exec("php {$composerInstaller} --install-dir=/tmp --filename=composer");
    $output = [];
    $status = 0;
    exec('php /tmp/composer install --no-dev --prefer-dist --ignore-platform-reqs', $output, $status);
    if ($status !== 0) {
        fwrite(STDERR, "Composer install failed:\n" . implode(PHP_EOL, $output) . "\n");
        exit($status);
    }
}

$commands = [
    'npm install',
    'npm run build',
];

foreach ($commands as $command) {
    $output = [];
    $status = 0;
    exec($command, $output, $status);
    if ($status !== 0) {
        fwrite(STDERR, "Command failed: {$command}\n" . implode(PHP_EOL, $output) . "\n");
        exit($status);
    }
}
