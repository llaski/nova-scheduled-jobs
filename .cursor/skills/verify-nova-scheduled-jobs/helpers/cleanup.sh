#!/usr/bin/env bash
set -euo pipefail

PID_FILE="${PID_FILE:-/tmp/nova-scheduled-jobs-verify-serve.pid}"

if [[ -f "$PID_FILE" ]]; then
  pid=$(cat "$PID_FILE")
  if kill -0 "$pid" 2>/dev/null; then
    kill "$pid" || true
    # wait briefly
    for _ in $(seq 1 20); do
      kill -0 "$pid" 2>/dev/null || break
      sleep 0.2
    done
    if kill -0 "$pid" 2>/dev/null; then
      kill -9 "$pid" || true
    fi
    echo "stopped serve pid=$pid"
  else
    echo "pid file stale ($pid)"
  fi
  rm -f "$PID_FILE"
else
  echo "no pid file; left preexisting listeners alone"
fi

rm -rf /tmp/nova-scheduled-jobs-verify-profile-* 2>/dev/null || true
echo "cleanup done (evidence retained)"
