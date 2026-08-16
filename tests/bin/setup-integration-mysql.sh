#!/usr/bin/env bash

set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/../.." && pwd)"

FIXTURES_DIR="$ROOT_DIR/tests/Fixtures/mysql"
SCHEMA="$FIXTURES_DIR/schema.sql"
PDO_ROW="$ROOT_DIR/bin/pdo-row"
GENERATED_DIR="$FIXTURES_DIR/generated"

MYSQL_HOST="${MYSQL_HOST:-127.0.0.1}"
MYSQL_PORT="${MYSQL_PORT:-3306}"
MYSQL_DATABASE="${MYSQL_DATABASE:-pdo_row_test}"
MYSQL_USER="${MYSQL_USER:-pdo_row}"
MYSQL_PASSWORD="${MYSQL_PASSWORD:-pdo_row}"

echo "==> Preparing MySQL integration fixtures"

# ---------------------------------------------------------------------------
# Requirements
# ---------------------------------------------------------------------------

if ! command -v mysql >/dev/null 2>&1; then
    echo "Error: mysql client is required." >&2
    exit 1
fi

if ! command -v php >/dev/null 2>&1; then
    echo "Error: php is required." >&2
    exit 1
fi

if [ ! -f "$PDO_ROW" ]; then
    echo "Error: pdo-row executable not found:" >&2
    echo "       $PDO_ROW" >&2
    exit 1
fi

if [ ! -f "$SCHEMA" ]; then
    echo "Error: schema file not found:" >&2
    echo "       $SCHEMA" >&2
    exit 1
fi

# ---------------------------------------------------------------------------
# Check MySQL connection
# ---------------------------------------------------------------------------

echo "==> Checking MySQL connection"

MYSQL_PWD="$MYSQL_PASSWORD" mysql \
    --host="$MYSQL_HOST" \
    --port="$MYSQL_PORT" \
    --user="$MYSQL_USER" \
    --database="$MYSQL_DATABASE" \
    --batch \
    --skip-column-names \
    -e "SELECT 1" >/dev/null

# ---------------------------------------------------------------------------
# Clean previous generated classes
# ---------------------------------------------------------------------------

echo "==> Cleaning generated classes"

rm -rf "$GENERATED_DIR"

mkdir -p "$GENERATED_DIR"

# ---------------------------------------------------------------------------
# Create database schema and fixtures
# ---------------------------------------------------------------------------

echo "==> Creating MySQL schema"

MYSQL_PWD="$MYSQL_PASSWORD" mysql \
    --host="$MYSQL_HOST" \
    --port="$MYSQL_PORT" \
    --user="$MYSQL_USER" \
    --database="$MYSQL_DATABASE" \
    < "$SCHEMA"

# ---------------------------------------------------------------------------
# Generate TableRow classes
# ---------------------------------------------------------------------------

echo "==> Generating TableRow classes"

cd "$FIXTURES_DIR"

php "$PDO_ROW" generate --configuration="pdo-row.php"
php "$PDO_ROW" generate --configuration="pdo-row-stringified.php"

# ---------------------------------------------------------------------------
# Validate generated fixture
# ---------------------------------------------------------------------------

GENERATED_NATIVE_ROW="$GENERATED_DIR/Native/UsersTableRow.php"
if [ ! -f "$GENERATED_NATIVE_ROW" ]; then
    echo "Error: expected generated class was not created:" >&2
    echo "       $GENERATED_NATIVE_ROW" >&2
    exit 1
fi

GENERATED_STRINGIFIED_ROW="$GENERATED_DIR/Stringified/UsersTableRow.php"
if [ ! -f "$GENERATED_STRINGIFIED_ROW" ]; then
    echo "Error: expected generated class was not created:" >&2
    echo "       $GENERATED_STRINGIFIED_ROW" >&2
    exit 1
fi

echo "==> MySQL integration fixtures ready"
echo
echo "Database:"
echo "  $MYSQL_DATABASE"
echo
echo "Host:"
echo "  $MYSQL_HOST:$MYSQL_PORT"
