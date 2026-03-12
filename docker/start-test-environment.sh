#!/usr/bin/env bash

set -euo pipefail

OCC="docker compose exec --no-tty nextcloud php occ"

echo "Removing old containers..."
docker compose down --volumes
echo "OK"
echo ""

echo "Starting containers..."
docker compose up --detach --build
echo "OK"
echo ""

echo "Starting file sync (docker compose watch)..."
docker compose watch --no-up nextcloud >/dev/null 2>&1 &
echo "OK"
echo ""

echo "Waiting for Nextcloud to finish installing..."
until $OCC status --output=json | grep -q '"installed":true'; do
    sleep 1
done
echo "OK"
echo ""

echo "Disabling firstrunwizard..."
$OCC app:disable firstrunwizard
echo "OK"
echo ""

echo "Enabling codeinjector..."
$OCC app:enable codeinjector
echo "OK"
echo ""

echo "Done"
echo " URL: http://localhost:8080/settings/admin/codeinjector"
echo " Login: admin"
echo " Password: admin"
