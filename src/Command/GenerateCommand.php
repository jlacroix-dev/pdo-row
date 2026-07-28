<?php

declare(strict_types=1);

namespace JlacroixDev\PdoRow\Command;

use JlacroixDev\PdoRow\Config;
use JlacroixDev\PdoRow\Model\Column;
use JlacroixDev\PdoRow\Repository\PDO\MySQL\TableRow\ColumnsTableRow;
use JlacroixDev\PdoRow\Utils\TemplateRenderer;
use JlacroixDev\PdoRow\Version;
use PDO;
use PDOStatement;
use RuntimeException;

final class GenerateCommand implements Command
{
    public function __construct(
        private TemplateRenderer $renderer,
    ){

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
            assert(is_string($configPath));
            if (!file_exists($configPath)) {
                echo "Config file '$configPath' not found" . PHP_EOL;
                return self::FAILURE;
            }
        } else {
            $workdir = getcwd();
            $configPath = $workdir . '/pdo-row.php';
            if (!file_exists($configPath)) {
                // todo Call InitCommand
                echo "Config file not found. Create `pdo-row.php`" . PHP_EOL;
                return self::FAILURE;
            }
        }

        $configPath = realpath($configPath);
        echo "Note: Using configuration file $configPath" . PHP_EOL;
        $config = require_once $configPath;

        if (!$config instanceof Config) {
            echo "Config must be an instance of PDORowConfig" . PHP_EOL;
            return self::FAILURE;
        }

        echo $config . PHP_EOL;

        $directory = $config->getDirectory();
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true); // TODO: what permission?
        }
        if (!is_dir($directory)) {
            echo "'$directory' is not a directory" . PHP_EOL;
            return self::FAILURE;
        }

        echo "Start generating..." . PHP_EOL;

        $tables = $this->getTables($config);

        foreach ($tables as $tableName) {

            $sql = <<<SQL
SELECT *
FROM information_schema.columns
WHERE TABLE_SCHEMA = DATABASE()
AND TABLE_NAME = ?
ORDER BY ORDINAL_POSITION
SQL;

            $stmt = $config->getPdo()->prepare($sql);
            $stmt->execute([$tableName]);
            /** @var ColumnsTableRow[] $rows */
            $rows = $stmt->fetchAll(PDO::FETCH_CLASS, ColumnsTableRow::class);
            if (count($rows) === 0) {
                throw new RuntimeException("Table $tableName does not exist");
            }

            $className = $config->getNamingStrategy()->class($tableName);
            $columns = array_map(function (ColumnsTableRow $row): Column {
                return new Column(
                    $row->COLUMN_NAME ?? '',
                    $row->COLUMN_TYPE,
                    $row->IS_NULLABLE === 'YES',
                    $row->COLUMN_DEFAULT,
                    $row->COLUMN_COMMENT,
                );
            }, $rows);

            $code = $this->renderer->render($config->getTemplate(), [
                'version' => Version::VERSION,
                'namespace' => $config->getNamespace(),
                'className' => $className,
                'columns' => $columns,
            ]);

            $outputDir = $config->getDirectory();
            $outputFile = "{$outputDir}/{$className}.php";
            file_put_contents($outputFile, $code);

            echo "Generated {$outputFile}\n";
        }

        return self::SUCCESS;
    }

    /**
     * @return string[]
     */
    private function getTables(Config $config): array
    {
        if (!is_null($config->getOnlyTables())) {
            return $config->getOnlyTables();
        }

        $sql = <<<SQL
SELECT TABLE_NAME
FROM information_schema.tables
WHERE TABLE_SCHEMA = DATABASE()
ORDER BY TABLE_NAME
SQL;

        $stmt = $config->getPdo()->query($sql);
        assert($stmt instanceof PDOStatement);
        /** @var string[] $tables */
        $tables = $stmt
            ->fetchAll(PDO::FETCH_COLUMN);

        if (is_null($config->getExceptTables())) {
            return $tables;
        }

        $exceptTables = $config->getExceptTables();
        return array_diff($tables, $exceptTables);
    }
}