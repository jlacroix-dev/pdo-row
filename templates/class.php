<?php
/**
 * @var string $namespace
 * @var string $className
 * @var array<string, string> $properties
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
<?php foreach ($properties as $name => $type): ?>
    public readonly <?= $type ?> $<?= $name ?>;
<?php endforeach; ?>
}
