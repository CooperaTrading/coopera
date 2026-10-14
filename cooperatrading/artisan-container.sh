#!/usr/bin/env bash
# Wrapper to run Laravel artisan and composer commands inside the PHP container.
# Uses a custom image (cooperatrading-php) that has php-gd, pdo_mysql, etc.
# built on top of composer:latest. Build it once with:
#   docker build -t cooperatrading-php docker/
#
# Usage: ./artisan-container.sh <command> [args...]
# Examples:
#   ./artisan-container.sh php artisan migrate
#   ./artisan-container.sh composer require vendor/package

set -e

PROJECT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
USER_ID="$(id -u)"
GROUP_ID="$(id -g)"

IMAGE="${COOPERATRADING_IMAGE:-cooperatrading-php:latest}"

if ! docker image inspect "${IMAGE}" >/dev/null 2>&1; then
  echo "Image '${IMAGE}' not found. Building from docker/Dockerfile..." >&2
  docker build -t "${IMAGE}" "${PROJECT_DIR}/docker"
fi

docker run --rm \
  --user "${USER_ID}:${GROUP_ID}" \
  -v "${PROJECT_DIR}:/app" \
  -w /app \
  -e HOME=/tmp \
  "${IMAGE}" \
  "$@"
