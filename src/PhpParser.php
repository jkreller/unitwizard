<?php

namespace Jkreller\UnitWizard;

class PhpParser
{
    public function findNamespace(string $filePath): ?string
    {
        $contents = file_get_contents($filePath);
        $matches = [];
        preg_match('/namespace\s+(.*);/', $contents, $matches);
        return isset($matches[1]) ? trim($matches[1]) : null;
    }
}
