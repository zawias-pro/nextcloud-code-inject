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

echo "▶  Fixing app directory permissions for local dev…"
docker compose exec -T --user root nextcloud sh -c "chown -R www-data:www-data /var/www/html/apps && chmod -R u+rwX /var/www/html/apps"
# Avoid changing ownership of bind-mounted .git metadata from the host
docker compose exec -T --user root nextcloud sh -c "find /var/www/html/custom_apps -path '*/.git' -prune -o -exec chown www-data:www-data {} +"
docker compose exec -T --user root nextcloud sh -c "find /var/www/html/custom_apps -path '*/.git' -prune -o -exec chmod u+rwX {} +"

echo "▶  Disabling App Store in this test environment…"
$OCC config:system:set appstoreenabled --type=boolean --value=false

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
