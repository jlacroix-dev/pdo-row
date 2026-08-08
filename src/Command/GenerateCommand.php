<?php

declare(strict_types=1);

namespace JlacroixDev\PdoRow\Command;

use Exception;
use JlacroixDev\PdoRow\Config;
use JlacroixDev\PdoRow\Generation\TableFilter;
use JlacroixDev\PdoRow\Model\Column;
use JlacroixDev\PdoRow\Model\Table;
use JlacroixDev\PdoRow\TableInspector\TableInspector;
use JlacroixDev\PdoRow\Template\TemplateRenderer;
use JlacroixDev\PdoRow\Utils\Filesystem;
use JlacroixDev\PdoRow\Version;
use PDO;
use PDOStatement;
use RuntimeException;

final class GenerateCommand implements Command
{
    public function __construct(
        private readonly TableFilter $tableFilter,
        private readonly TableInspector $tableInspector,
        private readonly TemplateRenderer $renderer,
        private readonly Filesystem $filesystem,
    ) {
    }

    public static function name(): string
    {
        return 'generate';
    }

    public static function description(): string
    {
        return 'Generate Row object to use when querying DB with PDO';
    }

    private function usage(): void
    {
        echo <<<HELP
Description:
  Generate Row object to use when querying DB with PDO

Usage:
  pdo-row generate [options]

Options:
  --configuration=CONFIGURATION     Path to project configuration file, default to 'pdo-row.php'
  --help                            Display this help message
 
HELP;
    }

    public function run(array $argv): int
    {
        $options = getopt('', ['configuration::', "help"]);

        if (array_key_exists('help', $options)) {
            $this->usage();
            return self::SUCCESS;
        }

        if (array_key_exists('configuration', $options)) {
            $configPath = $options['configuration'];
            if (!is_string($configPath)) {
                throw new Exception('Configuration is not a string');
            }
            if (!$this->filesystem->exists($configPath)) {
                echo "Config file '$configPath' not found" . PHP_EOL;
                return self::FAILURE;
            }
        } else {
            $workdir = getcwd();
            $configPath = $workdir . '/pdo-row.php';
            if (!$this->filesystem->exists($configPath)) {
                // todo Call InitCommand
                echo "Config file not found. Create `pdo-row.php`" . PHP_EOL;
                return self::FAILURE;
            }
        }

        $configPath = realpath($configPath);
        echo "Note: Using configuration file $configPath" . PHP_EOL;
        $config = require $configPath;

        if (!$config instanceof Config) {
            echo "Config must be an instance of PDORowConfig" . PHP_EOL;
            return self::FAILURE;
        }

        echo $config . PHP_EOL;

        $directory = $config->getDirectory();
        $this->filesystem->ensureDirectory($directory);

        echo "Start generating..." . PHP_EOL;

        $tables = $this->tableInspector
            ->inspect($config->getPdo());

        $tables = $this->tableFilter->filter(
            $tables,
            $config->getOnlyTables(),
            $config->getExceptTables(),
        );

        foreach ($tables as $table) {
            $className = $config->getNamingStrategy()->class($table->name);

            $code = $this->renderer->render($config->getTemplate(), [
                'version' => Version::VERSION,
                'namespace' => $config->getNamespace(),
                'className' => $className,
                'columns' => $table->columns,
            ]);

            $outputDir = $config->getDirectory();
            $outputFile = "{$outputDir}/{$className}.php";
            $this->filesystem->write($outputFile, $code);

            echo "Generated {$outputFile}\n";
        }

        return self::SUCCESS;
    }
}
