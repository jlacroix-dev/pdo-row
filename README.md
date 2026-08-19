# PDO Row

Generate lightweight, typed PHP classes for use with PDO's `fetchObject()`.

PDO already supports hydrating query results into objects. **PDO Row** generates the classes for you based on your database schema.

## Requirements

* PHP 8.2 or later
* PDO
* MySQL or SQLite

## Installation

Install PDO Row as a development dependency:

```bash
composer require --dev jlacroix-dev/pdo-row
```

PDO Row is a code generator, so it normally belongs in `require-dev`.

## Quick Start

### 1. Create the configuration file

Run:

```bash
vendor/bin/pdo-row init
```

This creates a `pdo-row.php` file in the current directory.

The generated configuration uses the following environment variables:

```text
DB_HOST
DB_DATABASE
DB_USERNAME
DB_PASSWORD
DB_PORT
```

For MySQL, for example:

```bash
export DB_HOST=127.0.0.1
export DB_DATABASE=my_database
export DB_USERNAME=my_user
export DB_PASSWORD=my_password
export DB_PORT=3306
```

The generated configuration creates a PDO connection and configures the generated classes to be written to:

```text
src/Repository/PDO/TableRow
```

with the namespace:

```text
App\Repository\PDO\TableRow
```

You can edit `pdo-row.php` to customize these settings.

### 2. Generate the row classes

Run:

```bash
vendor/bin/pdo-row generate
```

PDO Row inspects the database schema and generates one PHP class for each selected table.

### 3. Hydrate a query result

Given this table:

```sql
CREATE TABLE users (
    id INT NOT NULL,
    name VARCHAR(255),
    email VARCHAR(255)
);
```

PDO Row can generate:

```php
final class UsersTableRow
{
    public int $id;
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

## Configuration

A configuration file is a PHP file returning a `Config` instance.

For example:

```php
<?php

declare(strict_types=1);

use JlacroixDev\PdoRow\Config\Config;

$pdo = new PDO(
    'mysql:host=127.0.0.1;port=3306;dbname=my_database;charset=utf8mb4',
    'my_user',
    'my_password',
);

return Config::configure($pdo)
    ->withDirectory(__DIR__ . '/src/Repository/PDO/TableRow')
    ->withNamespace('App\\Repository\\PDO\\TableRow');
```

### Output directory

Use `withDirectory()` to change where generated classes are written:

```php
->withDirectory(__DIR__ . '/src/Database/Rows')
```

### Namespace

Use `withNamespace()` to change the namespace of generated classes:

```php
->withNamespace('App\\Database\\Rows')
```

### Naming strategy

Use `withNamingStrategy()` to customize how database names are converted into PHP class and property names:

```php
->withNamingStrategy(
    new JlacroixDev\PdoRow\Naming\MyNaming()
)
```

Generated class and property names must be valid PHP identifiers.

### Selecting tables

Generate only specific tables:

```php
->onlyTables([
    'users',
    'orders',
])
```

`onlyTables()` and `exceptTables()` cannot be used together.

### Excluding tables

Exclude specific tables:

```php
->exceptTables([
    'migrations',
    'sessions',
])
```

### Custom template

PDO Row uses a PHP template to generate classes. You can provide your own template:

```php
->withTemplate(__DIR__ . '/templates/class.tpl.php')
```

## CLI

### Initialize configuration

```bash
vendor/bin/pdo-row init
```

Creates:

```text
pdo-row.php
```

The command fails if the configuration file already exists.

### Generate classes

```bash
vendor/bin/pdo-row generate
```

By default, PDO Row looks for:

```text
pdo-row.php
```

in the current working directory.

### Use another configuration file

```bash
vendor/bin/pdo-row generate --configuration=/path/to/pdo-row.php
```

### Show generation help

```bash
vendor/bin/pdo-row generate --help
```

### Show the installed version

```bash
vendor/bin/pdo-row version
```

## Types

Generated properties use database-aware PHP types based on the PDO driver and fetch configuration.

PDO's native result typing varies between drivers and configuration, so PDO Row resolves the generated property type from the actual PDO configuration rather than blindly mapping SQL types.

For example, depending on the database driver and PDO settings, an integer column may be generated as either `int` or `string`.

Nullable database columns are represented by nullable PHP property types.

## Database Support

Currently supported:

* MySQL
* SQLite (WIP)

The schema-inspection layer is designed so additional database drivers can be added independently.

## Generated Files

Generated classes are intended to be simple data objects for PDO hydration.

They are **not ORM entities**.

PDO Row does not provide:

* relationships
* persistence
* repositories
* a unit-of-work abstraction
* migrations
* query building

## License

PDO Row is released under the MIT license.

See [LICENSE](LICENSE).
