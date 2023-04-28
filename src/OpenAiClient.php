<?php

namespace Jkreller\UnitWizard;

use GuzzleHttp\Client;

final readonly class OpenAiClient
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://api.openai.com',
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer XXX'
            ]
        ]);
    }

    public function requestPhpUnitTestClass(string $originalFile, string $testsNamespace): string
    {
        $originalFileContent = file_get_contents($originalFile);

        $prompt = "Write a PHPUnit test class for the following PHP class. Consider writing multiple tests with different data sets and outcomes (positive, negative) if necessary. Use data providers if useful. Adjust the namespace to be within $testsNamespace:";
        $prompt .= "\n\n$originalFileContent";
        $prompt .= "\n\n<?php\n\n";

        return "<?php\n\n" . $this->requestApi($prompt, $originalFile);
    }

    private function requestApi(string $prompt, $file): string
    {
        //$response = $this->client->request('POST', '/v1/chat/completions', [
        //    'json' => [
        //        'model' => 'gpt-3.5-turbo',
        //        "messages" => [['role' => 'user', 'content' => $prompt]],
        //        'temperature' => 0.7,
        //    ]
        //]);
//
        //$responseBody = json_decode($response->getBody());
        //return $responseBody->choices[0]->message->content;

        $response = $this->client->request('POST', '/v1/engines/text-davinci-003/completions', [
            'json' => [
                'prompt' => $prompt,
                'max_tokens' => 3072,
                'temperature' => 0.7,
            ]
        ]);

        $responseBody = json_decode($response->getBody());

        return $responseBody->choices[0]->text;
    }
}
