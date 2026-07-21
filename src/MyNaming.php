<?php

namespace JlacroixDev\PdoRow;

use JlacroixDev\PdoRow\NamingStrategy;

final class MyNaming implements NamingStrategy
{
    public function class(string $table): string
    {
        $string = strtolower($table);
        $string = str_replace('_', ' ', $string);
        $string = ucwords($string);
        $string = str_replace(' ', '', $string);
        return $string . 'TableRow';
    }
}