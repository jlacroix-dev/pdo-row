<?php

declare(strict_types=1);

namespace JlacroixDev\PdoRow\Console\Command;

use InvalidArgumentException;

final class GenerateOptionsParser
{
    public function parse(): GenerateOptions
    {
        $options = getopt('', ['configuration::', 'help']);

        $configuration = $options['configuration'] ?? null;

        if ($configuration !== null && !is_string($configuration)) {
            throw new InvalidArgumentException('Configuration is not a string');
        }

        return new GenerateOptions(
            configuration: $configuration,
            help: array_key_exists('help', $options),
        );
    }
}
