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

echo "Waiting for Nextcloud to finish installing..."
until $OCC status --output=json | grep -q '"installed":true'; do
    sleep 1
done
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
