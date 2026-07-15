#!/usr/bin/env bash
set -euo pipefail

mkdir -p /tmp/storage/app/public /tmp/storage/framework/cache/data /tmp/storage/framework/sessions /tmp/storage/framework/testing /tmp/storage/framework/views /tmp/storage/logs

php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
  && php composer-setup.php --install-dir=/tmp --filename=composer \
  && php /tmp/composer install --no-dev --prefer-dist --ignore-platform-reqs

if [ ! -f .env ]; then
  cp .env.example .env
fi

php -r '
$envPath = ".env";
$env = file_exists($envPath) ? file_get_contents($envPath) : file_get_contents(".env.example");
$isVercel = getenv("VERCEL") || getenv("VERCEL_URL");
$defaults = [
    "APP_ENV" => getenv("APP_ENV") ?: "production",
    "APP_DEBUG" => getenv("APP_DEBUG") ?: "false",
    "APP_URL" => getenv("APP_URL") ?: (getenv("VERCEL_URL") ? "https://" . getenv("VERCEL_URL") : "https://localhost"),
    "DB_CONNECTION" => getenv("DB_CONNECTION") ?: ($isVercel ? "sqlite" : "pgsql"),
    "DB_DATABASE" => getenv("DB_DATABASE") ?: ($isVercel ? "/tmp/storage/database.sqlite" : "database"),
    "SESSION_DRIVER" => getenv("SESSION_DRIVER") ?: "file",
    "CACHE_STORE" => getenv("CACHE_STORE") ?: "file",
    "QUEUE_CONNECTION" => getenv("QUEUE_CONNECTION") ?: "sync",
];
foreach ($defaults as $key => $value) {
    $pattern = "/^" . preg_quote($key, "/") . "=.*/m";
    if ($isVercel) {
        $env = preg_replace($pattern, $key . "=" . $value, $env, 1);
        if (preg_match($pattern, $env) !== 1) {
            $env .= PHP_EOL . $key . "=" . $value;
        }
    } else {
        if (preg_match($pattern, $env) !== 1) {
            $env .= PHP_EOL . $key . "=" . $value;
        }
    }
}
file_put_contents($envPath, $env);
'

php artisan key:generate --force
php artisan storage:link --force
php artisan migrate --force
npm install
npm run build
