<?php

declare(strict_types=1);

use JlacroixDev\PdoRow\Command\DefaultCommand;

require_once __DIR__ . '/../vendor/autoload.php';

$application = new DefaultCommand();
$status = $application->run();
exit($status);
