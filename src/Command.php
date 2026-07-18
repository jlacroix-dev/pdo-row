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
  --configuration=CONFIGURATION     Path to project configuration file, default to 'pdo-row.php' or 'pdo-row.php.dist'
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
            $configPath = $this->getDefaultConfigPath();
            if (is_null($configPath)) {
                echo "Config file not found. Create `pdo-row.php` or `pdo-row.php.dist`" . PHP_EOL;
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
            $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $properties = [];
            foreach ($columns as $column) {
                $name = $column['COLUMN_NAME'];
                $type = $column['IS_NULLABLE'] === 'YES' ? '?string' : 'string';
                $properties[$name] = $type;
            }
            $className = $this->getClassName($table);

            $code = $this->render('templates/class.php', [
                'namespace' => $config->getNamespace(),
                'className' => $className,
                'properties' => $properties,
            ]);

            $outputDir = $config->getDirectory();
            $outputFile = "{$outputDir}/{$className}.php";
            file_put_contents($outputFile, $code);

            echo "Generated {$outputFile}\n";
        }

        return self::SUCCESS;
    }

    private function getDefaultConfigPath(): ?string
    {
        $configPaths = [
            'pdo-row.php',
            'pdo-row.php.dist',
        ];
        foreach ($configPaths as $configPath) {
            if (file_exists($configPath)) {
                return $configPath;
            }
        }
        return null;
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