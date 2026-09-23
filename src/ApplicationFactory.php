<?php

declare(strict_types=1);

namespace JlacroixDev\PdoRow;

use JlacroixDev\PdoRow\Config\ConfigLoader;
use JlacroixDev\PdoRow\Console\Command\GenerateCommand;
use JlacroixDev\PdoRow\Console\Command\GenerateOptionsParser;
use JlacroixDev\PdoRow\Console\Command\HelpCommand;
use JlacroixDev\PdoRow\Console\Command\InitCommand;
use JlacroixDev\PdoRow\Console\Command\VersionCommand;
use JlacroixDev\PdoRow\Console\Output;
use JlacroixDev\PdoRow\Filesystem\LocalFilesystem;
use JlacroixDev\PdoRow\Generation\GeneratedFileWriter;
use JlacroixDev\PdoRow\TableInspector\MysqlSchemaInspector;
use JlacroixDev\PdoRow\TableInspector\SqliteSchemaInspector;
use JlacroixDev\PdoRow\TableInspector\TableInspector;
use JlacroixDev\PdoRow\Type\MysqlPhpTypeResolver;
use JlacroixDev\PdoRow\Type\PhpTypeResolverCollection;
use JlacroixDev\PdoRow\Type\SqlitePhpTypeResolver;

final class ApplicationFactory
{
    public static function create(): Application
    {
        $tableInspector = new TableInspector([
            new MysqlSchemaInspector(),
            new SqliteSchemaInspector(),
        ]);

        $phpTypeResolvers = new PhpTypeResolverCollection([
            new MysqlPhpTypeResolver(),
            new SqlitePhpTypeResolver(),
        ]);

        $filesystem = new LocalFilesystem();
        $writer = new GeneratedFileWriter($filesystem);
        $output = new Output();

        $commands = [
            new InitCommand($filesystem, $output),
            new GenerateCommand(
                new GenerateOptionsParser(),
                new ConfigLoader($filesystem),
                $tableInspector,
                $phpTypeResolvers,
                $writer,
                $filesystem,
                $output,
            ),
            new VersionCommand(Package::version(), $output),
        ];
        $commands[] = new HelpCommand($commands, $output);

        return new Application($commands);
    }
}
