<?php

namespace Jkreller\UnitWizard\Command;

use Jkreller\UnitWizard\FileHandler;
use Jkreller\UnitWizard\OpenAiClient;

final readonly class GeneratePhpUnitTestsCommand
{
    public function __construct(
        private FileHandler  $fileHandler,
        private OpenAiClient $openAiClient,
    )
    {
    }

    public function execute(array $argv): void
    {
        if (empty($argv)) {
            throw new \InvalidArgumentException('No arguments provided');
        }

        $projectPath = $argv[1];
        $testsNamespace = $argv[2];

        if (!$projectPath || !$testsNamespace) {
            throw new \InvalidArgumentException('Not enough arguments provided');
        }

        $files = $this->fileHandler->readDirectory($projectPath);

        foreach($files as $file) {
            $phpUnitTestClass = $this->openAiClient->requestPhpUnitTestClass($file, $testsNamespace);
            $this->fileHandler->writeTestFile($file, $phpUnitTestClass);
        }
    }
}
