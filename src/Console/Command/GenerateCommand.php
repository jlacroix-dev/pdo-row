<?php

declare(strict_types=1);

namespace JlacroixDev\PdoRow\Console\Command;

use JlacroixDev\PdoRow\Config\ConfigLoader;
use JlacroixDev\PdoRow\Console\Output;
use JlacroixDev\PdoRow\Filesystem\Filesystem;
use JlacroixDev\PdoRow\Generation\GeneratedFile;
use JlacroixDev\PdoRow\Generation\GeneratedFileWriter;
use JlacroixDev\PdoRow\Generation\TableFilter;
use JlacroixDev\PdoRow\Model\Column;
use JlacroixDev\PdoRow\Model\DatabaseColumn;
use JlacroixDev\PdoRow\TableInspector\TableInspector;
use JlacroixDev\PdoRow\Template\TemplateRenderer;
use JlacroixDev\PdoRow\Package;
use JlacroixDev\PdoRow\Type\FetchTypeConfiguration;
use JlacroixDev\PdoRow\Type\PhpTypeResolverCollection;
use PDO;

final readonly class GenerateCommand implements Command
{
    public function __construct(
        private GenerateOptionsParser $optionsParser,
        private ConfigLoader $configLoader,
        private TableFilter $tableFilter,
        private TableInspector $tableInspector,
        private PhpTypeResolverCollection $phpTypeResolvers,
        private TemplateRenderer $renderer,
        private GeneratedFileWriter $writer,
        private Filesystem $filesystem,
        private Output $output,
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
        $this->output->write($usage);
    }

    public function run(array $argv): int
    {
        $options = $this->optionsParser->parse($argv);

        if ($options->help) {
            $this->usage();
            return self::SUCCESS;
        }

        $config = $this->configLoader->load($options->configuration);

        $this->output->write($config->__toString());

        $directory = $config->getDirectory();
        $this->filesystem->ensureDirectory($directory);

        $this->output->write('Start generating...');

        $pdo = $config->getPdo();
        $tables = $this->tableInspector
            ->inspect($pdo);

        $tables = $this->tableFilter->filter(
            $tables,
            $config->getOnlyTables(),
            $config->getExceptTables(),
        );

        $driverName = (string) $pdo->getAttribute(
            PDO::ATTR_DRIVER_NAME
        );

        $fetchTypeConfiguration = new FetchTypeConfiguration(
            stringifyFetches: (bool) $pdo->getAttribute(
                PDO::ATTR_STRINGIFY_FETCHES
            ),
        );

        $files = [];
        foreach ($tables as $table) {
            $columns = array_map(
                fn (DatabaseColumn $column): Column => new Column(
                    name: $column->name,
                    databaseType: $column->databaseType,
                    phpType: $this->phpTypeResolvers->resolve(
                        $driverName,
                        $column,
                        $fetchTypeConfiguration,
                    ),
                    nullable: $column->nullable,
                ),
                $table->columns,
            );

            $className = $config->getNamingStrategy()->class($table->name);
            $filename = "{$className}.php";

            $code = $this->renderer->render($config->getTemplate(), [
                'version' => Package::version(),
                'namespace' => $config->getNamespace(),
                'className' => $className,
                'columns' => $columns,
            ]);

            $files[] = new GeneratedFile($filename, $code);
        }

        $this->writer->write(
            $config->getDirectory(),
            $files,
        );

        return self::SUCCESS;
    }
}
