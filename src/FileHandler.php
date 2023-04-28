<?php

namespace Jkreller\UnitWizard;

final readonly class FileHandler {
    public function readDirectory(string $directory): array
    {
        $files = [];
        foreach(glob($directory . '/*') as $file) {
            if(is_dir($file)) {
                $files = array_merge($files, $this->readDirectory($file));
            } else {
                $files[] = $file;
            }
        }

        return $files;
    }

    public function writeTestFile(string $originalPath, string $content): void
    {
        $pathParts = pathinfo($originalPath);
        $newFilePath = str_replace('src', 'tests', $pathParts['dirname']) . "/{$pathParts['filename']}Test.php";

        $newDirectory = pathinfo($newFilePath)['dirname'];
        if (!is_dir($newDirectory)) {
            mkdir($newDirectory, 0777, true);
        }

        file_put_contents($newFilePath, $content);
    }
}
