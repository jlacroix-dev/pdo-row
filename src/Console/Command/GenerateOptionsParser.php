<?php

declare(strict_types=1);

namespace JlacroixDev\PdoRow\Console\Command;

use InvalidArgumentException;

final class GenerateOptionsParser
{
    /**
     * @param string[] $argv
     */
    public function parse(array $argv): GenerateOptions
    {
        $configuration = null;
        $help = false;

        foreach (array_slice($argv, 2) as $index => $argument) {
            if ($argument === '--help') {
                $help = true;

                continue;
            }

            if ($argument === '--configuration') {
                $value = $argv[$index + 3] ?? null;

                if ($value === null || str_starts_with($value, '--')) {
                    throw new InvalidArgumentException(
                        'The --configuration option requires a value.'
                    );
                }

                $configuration = $value;

                continue;
            }

            if (str_starts_with($argument, '--configuration=')) {
                $configuration = substr(
                    $argument,
                    strlen('--configuration='),
                );

                if ($configuration === '') {
                    throw new InvalidArgumentException(
                        'The --configuration option requires a value.'
                    );
                }

                continue;
            }

            throw new InvalidArgumentException(
                "Unknown option '{$argument}'."
            );
        }

        return new GenerateOptions(
            configuration: $configuration,
            help: $help,
        );
    }
}
