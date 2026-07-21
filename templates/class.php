<?php

use JlacroixDev\PdoRow\Column;

/**
 * @var string $namespace
 * @var string $className
 * @var Column[] $columns
 */

?>
<?= '<?php' ?>

declare(strict_types=1);

namespace <?= $namespace ?>;

/**
 * This file is generated automatically by jlacroix-dev/pdo-row
 */
class <?= $className . PHP_EOL ?>
{
<?php foreach ($columns as $column): ?>
    // <?= $column->type ?> <?= $column->nullable ? 'NULL' : 'NOT NULL' ?>

    public readonly <?= $column->nullable ? '?string' : 'string' ?> $<?= $column->name ?>;

<?php endforeach; ?>
}
