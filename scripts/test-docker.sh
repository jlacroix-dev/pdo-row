#!/usr/bin/env bash

set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"

cd "$ROOT_DIR"

usage() {
    echo "Usage:"
    echo "  $0 <php-version>"
    echo "  $0 all"
    echo
    echo "Supported PHP versions:"
    echo "  8.2"
    echo "  8.3"
    echo "  8.4"
    echo "  8.5"
}

test_version() {
    local version="$1"

    echo
    echo "========================================"
    echo "Testing PHP ${version}"
    echo "========================================"
    echo

    PHP_VERSION="$version" docker compose run --rm php \
        composer install --no-interaction --prefer-dist

#    PHP_VERSION="$version" docker compose run --rm php \
#        vendor/bin/phpunit --version

    PHP_VERSION="$version" docker compose run --rm php \
        composer test
}

case "${1:-}" in
    8.2|8.3|8.4|8.5)
        test_version "$1"
        ;;

    all)
        for version in 8.2 8.3 8.4 8.5; do
            test_version "$version"
        done
        ;;

    *)
        usage
        exit 1
        ;;
esac
