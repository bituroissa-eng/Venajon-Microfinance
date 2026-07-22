#!/usr/bin/env bash
set -euo pipefail

project_root="$(cd "$(dirname "$0")/.." && pwd)"
storage_path="${STORAGE_PATH:-/tmp/storage}"

mkdir -p "$storage_path/app/public" \
  "$storage_path/framework/cache/data" \
  "$storage_path/framework/sessions" \
  "$storage_path/framework/testing" \
  "$storage_path/framework/views" \
  "$storage_path/logs"

export STORAGE_PATH="$storage_path"

if [ -f "$project_root/.env" ]; then
  if [ -z "${CACHE_STORE:-}" ]; then
    echo "CACHE_STORE=file" >> "$project_root/.env"
  fi
  if [ -z "${SESSION_DRIVER:-}" ]; then
    echo "SESSION_DRIVER=file" >> "$project_root/.env"
  fi
  if [ -z "${DB_CONNECTION:-}" ] && { [ -n "${DATABASE_URL:-}" ] || [ -n "${DB_URL:-}" ]; }; then
    echo "DB_CONNECTION=pgsql" >> "$project_root/.env"
  fi
fi

if [ -f "$project_root/.env" ]; then
  if grep -q '^APP_ENV=' "$project_root/.env"; then
    sed -i "s|^APP_ENV=.*|APP_ENV=${APP_ENV:-production}|" "$project_root/.env"
  else
    echo "APP_ENV=${APP_ENV:-production}" >> "$project_root/.env"
  fi

  if grep -q '^APP_DEBUG=' "$project_root/.env"; then
    sed -i "s|^APP_DEBUG=.*|APP_DEBUG=${APP_DEBUG:-false}|" "$project_root/.env"
  else
    echo "APP_DEBUG=${APP_DEBUG:-false}" >> "$project_root/.env"
  fi
fi

echo "Vercel setup completed"
