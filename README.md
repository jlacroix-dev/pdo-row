# PDO Row

Generate lightweight, typed PHP classes for use with PDO's `fetchObject()`.

PDO already supports hydrating query results into objects. **PDO Row** generates the classes for you, based on your database schema.

## Installation

```bash
composer require --dev jlacroix-dev/pdo-row
```

## Usage

Configure the database connection and generate your row classes:

```bash
vendor/bin/pdo-row generate
```

A table such as:

```sql
CREATE TABLE users (
    id INT NOT NULL,
    name VARCHAR(255),
    email VARCHAR(255)
);
```

can generate:

```php
final class UsersTableRow
{
    public string $id;
    public ?string $name;
    public ?string $email;
}
```

You can then hydrate query results directly with PDO:

```php
$statement = $pdo->query(
    'SELECT id, name, email FROM users'
);

$user = $statement->fetchObject(UsersTableRow::class);
```

## Types

Generated properties use `string` and `?string` intentionally.

PDO's native result typing varies between drivers and configuration. PDO Row therefore keeps generated property types predictable and backwards-compatible rather than attempting to infer PHP types from database column types.

Native database-to-PHP type mapping may be introduced as a future, opt-in feature.

## Database Support

Currently supported:

* MySQL
* SQLite

The schema-inspection layer is designed to allow additional database drivers to be added independently.

## Naming

PDO Row can transform database table and column names into PHP class and property names using configurable naming strategies.

Generated names must be valid PHP identifiers.

## Generated Files

Generated classes are intended to be simple data objects for PDO hydration.

They are **not ORM entities** and PDO Row does not attempt to provide relationships, persistence, repositories, or a unit-of-work abstraction.

## License

See [LICENSE](LICENSE).
