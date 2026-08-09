#!/usr/bin/env bash

set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/../.." && pwd)"

FIXTURES_DIR="$ROOT_DIR/tests/Fixtures/sqlite"
DATABASE="$FIXTURES_DIR/database.sqlite"
SCHEMA="$FIXTURES_DIR/schema.sql"
PDO_ROW="$ROOT_DIR/bin/pdo-row"
GENERATED_DIR="$FIXTURES_DIR/generated"

echo "==> Preparing SQLite integration fixtures"

# ---------------------------------------------------------------------------
# Requirements
# ---------------------------------------------------------------------------

if ! command -v sqlite3 >/dev/null 2>&1; then
    echo "Error: sqlite3 is required." >&2
    exit 1
fi

if [ ! -x "$PDO_ROW" ]; then
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
# Clean previous fixtures
# ---------------------------------------------------------------------------

echo "==> Cleaning previous database and generated classes"

rm -f "$DATABASE"
rm -rf "$GENERATED_DIR"

mkdir -p "$GENERATED_DIR"

# ---------------------------------------------------------------------------
# Create database
# ---------------------------------------------------------------------------

echo "==> Creating database"

sqlite3 "$DATABASE" < "$SCHEMA"

# ---------------------------------------------------------------------------
# Generate TableRow classes
# ---------------------------------------------------------------------------

echo "==> Generating TableRow classes"

cd "$FIXTURES_DIR"

php "$PDO_ROW" generate

# ---------------------------------------------------------------------------
# Validate generated fixture
# ---------------------------------------------------------------------------

GENERATED_ROW="$GENERATED_DIR/UsersTableRow.php"

if [ ! -f "$GENERATED_ROW" ]; then
    echo "Error: expected generated class was not created:" >&2
    echo "       $GENERATED_ROW" >&2
    exit 1
fi

echo "==> Integration fixtures ready"
echo
echo "Database:  $DATABASE"
echo "Generated: $GENERATED_ROW"
