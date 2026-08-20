<?php

use JlacroixDev\PdoRow\Model\Column;

/**
 * @var string $version
 * @var string $namespace
 * @var string $className
 * @var Column[] $columns
 */

?>
<?= '<?php' . PHP_EOL ?>

/**
 * This file was generated automatically.
 *
 * Generator: jlacroix-dev/pdo-row
 * Version: <?= $version . PHP_EOL ?>
 *
 * DO NOT EDIT MANUALLY.
 */

declare(strict_types=1);

namespace <?= $namespace ?>;

/**
 * Use with PDO::FETCH_ASSOC
 * @phpstan-type <?= $className ?>Assoc array{
<?php foreach ($columns as $column) : ?>
 *      <?= $column->name ?>: <?= $column->phpType . ($column->nullable ? '|null' : '') ?>,
<?php endforeach; ?>
 * }
 */
final class <?= $className . PHP_EOL ?>
{
<?php foreach ($columns as $column) : ?>
    // <?= $column->databaseType ?> <?= $column->nullable ? 'NULL' : 'NOT NULL' ?><?= PHP_EOL ?>
    public <?= $column->phpType . ($column->nullable ? '|null' : '') ?> $<?= $column->name ?>;
<?php endforeach; ?>
}
