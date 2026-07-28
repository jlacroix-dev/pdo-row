<?php

use JlacroixDev\PdoRow\Model\Column;

/**
 * @var string $version
 * @var string $namespace
 * @var string $className
 * @var Column[] $columns
 */

?>
<?= '<?php' ?>

declare(strict_types=1);

namespace <?= $namespace ?>;

/**
 * This file was generated automatically.
 *
 * Generator: jlacroix-dev/pdo-row
 * Version: <?= $version . PHP_EOL ?>
 *
 * DO NOT EDIT MANUALLY.
 */
final class <?= $className . PHP_EOL ?>
{
<?php foreach ($columns as $column): ?>
    // <?= $column->type ?> <?= $column->nullable ? 'NULL' : 'NOT NULL' ?><?= PHP_EOL ?>
    public <?= $column->nullable ? '?string' : 'string' ?> $<?= $column->name ?>;
<?php endforeach; ?>
}
