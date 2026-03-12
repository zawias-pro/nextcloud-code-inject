#!/usr/bin/env bash

set -euo pipefail

OCC="docker compose exec --no-tty nextcloud php occ"

echo "Removing old containers..."
docker compose down --volumes
echo "OK"
echo ""

echo "Starting containers..."
docker compose up --detach
echo "OK"
echo ""

echo "Waiting for the database to be healthy..."
until docker compose exec --no-tty db healthcheck.sh --connect --innodb_initialized; do
    sleep 5
done
echo "OK"
echo ""

echo "Waiting for Nextcloud to finish installing (this may take a minute)..."
until $OCC status --output=json | grep -q '"installed":true'; do
    sleep 5
done
echo "OK"
echo ""

echo "Fixing app directory permissions for local dev..."
docker compose exec -T --user root nextcloud sh -c "chown -R www-data:www-data /var/www/html/apps && chmod -R u+rwX /var/www/html/apps"
docker compose exec -T --user root nextcloud sh -c "find /var/www/html/custom_apps -path '*/.git' -prune -o -exec chown www-data:www-data {} +"
docker compose exec -T --user root nextcloud sh -c "find /var/www/html/custom_apps -path '*/.git' -prune -o -exec chmod u+rwX {} +"
echo "OK"
echo ""

echo "Enabling codeinjector..."
$OCC app:enable codeinjector
echo "OK"
echo ""

echo "Done"
echo " URL: http://localhost:8080"
echo " Login: admin"
echo " Password: admin"
