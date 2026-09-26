<?php

declare(strict_types=1);

namespace JlacroixDev\PdoRow;

use JlacroixDev\PdoRow\Config\ConfigLoader;
use JlacroixDev\PdoRow\Console\Command\Command;
use JlacroixDev\PdoRow\Console\Command\GenerateCommand;
use JlacroixDev\PdoRow\Console\Command\GenerateOptionsParser;
use JlacroixDev\PdoRow\Filesystem\LocalFilesystem;
use RuntimeException;

final class Application
{
    public function __construct(
        private LocalFilesystem $filesystem,
    ) {
    }

    /**
     * @param string[] $argv
     */
    public function run(array $argv): int
    {
        $name = $argv[1] ?? 'help';
        return match ($name) {
            'init' => $this->init(),
            'generate' => $this->generate($argv),
            'version' => $this->version(),
            'help' => $this->help(),
            default => throw new RuntimeException("Unknown command '{$name}'."),
        };
    }

    private function init(): int
    {
        $filename = getcwd() . '/pdo-row.php';

        if ($this->filesystem->exists($filename)) {
            echo 'Configuration already exists' . PHP_EOL;
            return Command::FAILURE;
        }

        $source = __DIR__ . '/../templates/pdo-row.tpl.php';
        $this->filesystem->copy($source, $filename);

        echo "Created {$filename}" . PHP_EOL;

        return Command::SUCCESS;
    }

    /**
     * @param string[] $argv
     */
    private function generate(array $argv): int
    {
        $command = new GenerateCommand(
            new GenerateOptionsParser(),
            new ConfigLoader($this->filesystem),
            $this->filesystem,
        );
        return $command->run($argv);
    }

    private function version(): int
    {
        echo 'pdo-row ' . Package::version() . PHP_EOL;
        return Command::SUCCESS;
    }

    private function help(): int
    {
        // TODO
        return Command::SUCCESS;
    }
}
