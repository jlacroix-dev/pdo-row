<?php

declare(strict_types=1);

namespace JlacroixDev\PdoRow;

use PDO;

final class Command
{
    public const int SUCCESS = 0;
    public const int FAILURE = 1;

    private function usage(): void
    {
        echo <<<HELP
Description:
  Generate Row object to use when querying DB with PDO

Usage:
  pdo-row [options]

Options:
  --configuration=CONFIGURATION     Path to project configuration file, default to 'pdo-row.php'
  --help                            Display this help message
 
HELP;
    }

    public function run(): int
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

        $sql = <<<SQL
SELECT TABLE_NAME
FROM information_schema.tables
WHERE TABLE_SCHEMA = DATABASE()
ORDER BY TABLE_NAME
SQL;
        $tables = $config->getPdo()->query($sql)
            ->fetchAll(PDO::FETCH_COLUMN);

        foreach ($tables as $table) {
            $sql = <<<SQL
SELECT
    COLUMN_NAME,
    DATA_TYPE,
    COLUMN_TYPE,
    IS_NULLABLE,
    COLUMN_COMMENT
FROM information_schema.columns
WHERE TABLE_SCHEMA = DATABASE()
AND TABLE_NAME = ?
ORDER BY ORDINAL_POSITION
SQL;

            $stmt = $config->getPdo()->prepare($sql);
            $stmt->execute([$table]);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $className = $config->getNamingStrategy()->class($table);
            $columns = array_map(function ($row): Column {
                return new Column(
                    $row['COLUMN_NAME'],
                    $row['COLUMN_TYPE'],
                    $row['IS_NULLABLE'] === 'YES',
                    '',
                    $row['COLUMN_COMMENT'],
                );
            }, $rows);

            $code = $this->render($config->getTemplate(), [
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

    private function render(string $template, array $data): string
    {
        extract($data, EXTR_SKIP);

        ob_start();
        include $template;
        return ob_get_clean();
    }

    private function getClassName(string $tableName): string
    {
        $string = strtolower($tableName);
        $string = str_replace('_', ' ', $string);
        $string = ucwords($string);
        $string = str_replace(' ', '', $string);
        return $string . 'TableRow';
    }
}