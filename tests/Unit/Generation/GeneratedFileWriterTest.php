<?php

declare(strict_types=1);

namespace Tests\Unit\Generation;

use JlacroixDev\PdoRow\Filesystem\Filesystem;
use JlacroixDev\PdoRow\Generation\GeneratedFile;
use JlacroixDev\PdoRow\Generation\GeneratedFileWriter;
use PHPUnit\Framework\TestCase;

class GeneratedFileWriterTest extends TestCase
{
    public function testWrite(): void
    {
        $directory = 'src/Repository/PDO/TableRow';
        $files = [
            new GeneratedFile('UsersTableRow.php', 'UsersTableRow content'),
            new GeneratedFile('RolesTableRow.php', 'RolesTableRow content'),
        ];
        $filesystem = self::createMock(Filesystem::class);

        $filesystem->expects($this->once())
            ->method('ensureDirectory')
            ->with($directory);

        $writtenFiles = [];

        $filesystem
            ->expects(self::exactly(2))
            ->method('write')
            ->willReturnCallback(
                function (string $filename, string $content) use (&$writtenFiles): void {
                    $writtenFiles[$filename] = $content;
                }
            );

        $writer = new GeneratedFileWriter($filesystem);
        $writer->write($directory, $files);

        $expected = [
            'src/Repository/PDO/TableRow/UsersTableRow.php' => 'UsersTableRow content',
            'src/Repository/PDO/TableRow/RolesTableRow.php' => 'RolesTableRow content',
        ];
        self::assertSame($expected, $writtenFiles);
    }
}
