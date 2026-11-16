#!/usr/bin/env bash
set -euo pipefail

# Package root: .../nova-scheduled-jobs (.cursor/skills/verify-*/helpers → ../../../../)
PKG_ROOT="$(cd "$(dirname "$0")/../../../.." && pwd)"
DEMO_DIR="${DEMO_DIR:-$(cd "$PKG_ROOT/../laravel-nova-demo" && pwd)}"
PORT="${PORT:-8000}"
HOST="${HOST:-127.0.0.1}"
PID_FILE="${PID_FILE:-/tmp/nova-scheduled-jobs-verify-serve.pid}"
PHP_BIN="${PHP_BIN:-}"

if [[ -z "$PHP_BIN" ]]; then
  if command -v php8.4 >/dev/null 2>&1; then PHP_BIN=php8.4
  elif command -v php8.3 >/dev/null 2>&1; then PHP_BIN=php8.3
  else PHP_BIN=php
  fi
fi

cd "$DEMO_DIR"

if [[ ! -f .env ]]; then
  cp .env.example .env
  "$PHP_BIN" artisan key:generate --force
fi

mkdir -p database
[[ -f database/database.sqlite ]] || touch database/database.sqlite
"$PHP_BIN" artisan migrate --force >/dev/null
"$PHP_BIN" artisan db:seed --force >/dev/null

if [[ -f "$PID_FILE" ]] && kill -0 "$(cat "$PID_FILE")" 2>/dev/null; then
  echo "serve already running pid=$(cat "$PID_FILE") url=http://${HOST}:${PORT}"
  exit 0
fi

# Reuse an already-bound port without our pid file
if curl -s -o /dev/null -w '%{http_code}' "http://${HOST}:${PORT}/nova/login" | grep -q 200; then
  echo "ready url=http://${HOST}:${PORT} (preexisting listener)"
  exit 0
fi

"$PHP_BIN" artisan serve --host="$HOST" --port="$PORT" >/tmp/nova-scheduled-jobs-verify-serve.log 2>&1 &
echo $! >"$PID_FILE"

for _ in $(seq 1 30); do
  code=$(curl -s -o /dev/null -w '%{http_code}' "http://${HOST}:${PORT}/nova/login" || true)
  if [[ "$code" == "200" ]]; then
    echo "ready url=http://${HOST}:${PORT} pid=$(cat "$PID_FILE")"
    exit 0
  fi
  sleep 0.5
done

echo "launch failed; last log:" >&2
tail -50 /tmp/nova-scheduled-jobs-verify-serve.log >&2 || true
exit 1
