<?php

declare(strict_types=1);

namespace JlacroixDev\PdoRow\Console\Command;

use JlacroixDev\PdoRow\Config\ConfigLoader;
use JlacroixDev\PdoRow\Database\MysqlDatabase;
use JlacroixDev\PdoRow\Database\SqliteDatabase;
use JlacroixDev\PdoRow\Filesystem\Filesystem;
use JlacroixDev\PdoRow\Package;
use PDO;
use RuntimeException;

final readonly class GenerateCommand implements Command
{
    public function __construct(
        private GenerateOptionsParser $optionsParser,
        private ConfigLoader $configLoader,
        private Filesystem $filesystem,
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
        $usage = <<<HELP
Description:
  Generate Row object to use when querying DB with PDO

Usage:
  pdo-row generate [options]

Options:
  --configuration=CONFIGURATION     Path to project configuration file, default to 'pdo-row.php'
  --help                            Display this help message
 
HELP;
        echo $usage . PHP_EOL;
    }

    public function run(array $argv): int
    {
        $options = $this->optionsParser->parse($argv);

        if ($options->help) {
            $this->usage();
            return self::SUCCESS;
        }

        $config = $this->configLoader->load($options->configuration);

        echo $config->__toString() . PHP_EOL;

        $directory = $config->directory;
        $this->filesystem->ensureDirectory($directory);

        echo 'Start generating...' . PHP_EOL;

        $pdo = $config->pdo;

        /** @var string $driverName */
        $driverName = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);

        $database = match ($driverName) {
            'mysql' => new MysqlDatabase(),
            'sqlite' => new SqliteDatabase(),
            default => throw new RuntimeException("Unsupported PDO driver: $driverName"),
        };

        $tables = $database->inspect($pdo);
        foreach ($tables as $table) {
            $className = $this->className($table->name);
            $filename = "{$className}.php";

            $code = $this->render(__DIR__ . '/../../../templates/class.tpl.php', [
                'version' => Package::version(),
                'namespace' => $config->namespace,
                'className' => $className,
                'columns' => $table->columns,
            ]);

            $path = "{$directory}/{$filename}";
            $this->filesystem->write($path, $code);
        }

        return self::SUCCESS;
    }

    private function className(string $table): string
    {
        $string = strtolower($table);
        $string = str_replace('_', ' ', $string);
        $string = ucwords($string);
        $string = str_replace(' ', '', $string);
        return $string . 'TableRow';
    }

    /**
     * @param array<string, mixed> $variables
     */
    public function render(string $template, array $variables = []): string
    {
        extract($variables, EXTR_SKIP);
        ob_start();
        require $template;
        return ob_get_clean();
    }
}
