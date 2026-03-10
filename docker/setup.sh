#!/usr/bin/env bash
# ---------------------------------------------------------------------------
# setup.sh — first-run helper
#
# Starts the stack, waits for Nextcloud to finish its auto-installation,
# then enables the codeinjector app.
#
# Usage:
#   cd docker/
#   ./setup.sh
# ---------------------------------------------------------------------------

set -euo pipefail

OCC="docker compose exec -T nextcloud php occ"

echo "▶  Starting containers…"
docker compose up -d

echo "▶  Waiting for the database to be healthy…"
until docker compose exec -T db healthcheck.sh --connect --innodb_initialized &>/dev/null; do
    sleep 2
    printf '.'
done
echo " ready."

echo "▶  Waiting for Nextcloud to finish installing (this may take a minute)…"
until $OCC status --output=json 2>/dev/null | grep -q '"installed":true'; do
    sleep 3
    printf '.'
done
echo " ready."

echo "▶  Enabling codeinjector…"
$OCC app:enable codeinjector

echo ""
echo "✔  Done!"
echo ""
echo "   URL  : http://localhost:${NC_PORT:-8080}"
echo "   Login: admin / admin  (or whatever you set in .env)"
echo ""
echo "   Admin settings → Code Injector"
echo ""
echo "   Useful commands (run from docker/):"
echo "     docker compose logs -f nextcloud     — live PHP/Apache log"
echo "     docker compose exec nextcloud php occ app:list"
echo "     docker compose down -v               — full teardown (removes DB)"
