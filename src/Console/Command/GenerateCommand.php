<?php

declare(strict_types=1);

namespace JlacroixDev\PdoRow\Console\Command;

use JlacroixDev\PdoRow\Config\ConfigLoader;
use JlacroixDev\PdoRow\Console\Output;
use JlacroixDev\PdoRow\Filesystem\Filesystem;
use JlacroixDev\PdoRow\Generation\GeneratedFile;
use JlacroixDev\PdoRow\Generation\GeneratedFileWriter;
use JlacroixDev\PdoRow\Generation\TableFilter;
use JlacroixDev\PdoRow\TableInspector\TableInspector;
use JlacroixDev\PdoRow\Template\TemplateRenderer;
use JlacroixDev\PdoRow\Package;

final readonly class GenerateCommand implements Command
{
    public function __construct(
        private GenerateOptionsParser $optionsParser,
        private ConfigLoader $configLoader,
        private TableFilter $tableFilter,
        private TableInspector $tableInspector,
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
        $options = $this->optionsParser->parse();

        if ($options->help) {
            $this->usage();
            return self::SUCCESS;
        }

        $config = $this->configLoader->load($options->configuration);

        $this->output->write($config->__toString());

        $directory = $config->getDirectory();
        $this->filesystem->ensureDirectory($directory);

        $this->output->write('Start generating...');

        $tables = $this->tableInspector
            ->inspect($config->getPdo());

        $tables = $this->tableFilter->filter(
            $tables,
            $config->getOnlyTables(),
            $config->getExceptTables(),
        );

        $files = [];
        foreach ($tables as $table) {
            $className = $config->getNamingStrategy()->class($table->name);
            $filename = "{$className}.php";

            $code = $this->renderer->render($config->getTemplate(), [
                'version' => Package::version(),
                'namespace' => $config->getNamespace(),
                'className' => $className,
                'columns' => $table->columns,
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
