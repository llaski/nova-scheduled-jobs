#!/usr/bin/env bash
set -euo pipefail

PKG_ROOT="$(cd "$(dirname "$0")/../../../.." && pwd)"
DEMO_DIR="${DEMO_DIR:-$(cd "$PKG_ROOT/../laravel-nova-demo" && pwd)}"
BASE_URL="${BASE_URL:-http://127.0.0.1:8000}"
PHP_BIN="${PHP_BIN:-}"

if [[ -z "$PHP_BIN" ]]; then
  if command -v php8.4 >/dev/null 2>&1; then PHP_BIN=php8.4
  elif command -v php8.3 >/dev/null 2>&1; then PHP_BIN=php8.3
  else PHP_BIN=php
  fi
fi

fail() { echo "doctor FAIL: $*" >&2; exit 1; }
ok() { echo "doctor OK: $*"; }

code=$(curl -s -o /dev/null -w '%{http_code}' "$BASE_URL/nova/login" || true)
[[ "$code" == "200" ]] || fail "login page HTTP $code at $BASE_URL/nova/login"
ok "login page 200"

body=$(curl -s "$BASE_URL/nova/login" || true)
echo "$body" | grep -qi 'email\|password\|Welcome\|Nova' || fail "login HTML missing Nova markers"
ok "login HTML looks like Nova"

cd "$DEMO_DIR"
nova_ver=$("$PHP_BIN" -r 'require "vendor/autoload.php"; echo \Composer\InstalledVersions::getPrettyVersion("laravel/nova");')
pkg_ver=$("$PHP_BIN" -r 'require "vendor/autoload.php"; echo \Composer\InstalledVersions::getPrettyVersion("llaski/nova-scheduled-jobs");')
fw_ver=$("$PHP_BIN" -r 'require "vendor/autoload.php"; echo \Composer\InstalledVersions::getPrettyVersion("laravel/framework");')
[[ "$nova_ver" == 5.* || "$nova_ver" == v5.* ]] || fail "expected Nova 5.x, got $nova_ver"
ok "Nova $nova_ver / Laravel $fw_ver / package $pkg_ver"

sched=$("$PHP_BIN" artisan schedule:list 2>/dev/null || true)
echo "$sched" | grep -q 'inspire\|demo:heartbeat\|PruneDemoRecords' || fail "schedule:list missing demo events"
ok "schedule:list has demo events"

echo "doctor PASS base=$BASE_URL"
