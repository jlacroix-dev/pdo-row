<?php

declare(strict_types=1);

$composer = json_decode(
    file_get_contents(__DIR__ . '/../composer.json'),
    true,
    flags: JSON_THROW_ON_ERROR,
);

$version = $composer['version'] ?? 'dev';

$contents = <<<PHP
<?php

declare(strict_types=1);

namespace JlacroixDev\PdoRow;

final class Version
{
    public const string VERSION = '{$version}';
}

PHP;

file_put_contents(
    __DIR__ . '/../src/Version.php',
    $contents,
);
