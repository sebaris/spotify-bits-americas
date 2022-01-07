<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class AuthenticationService
{
    private $httpClient;
    private $key;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
        $this->key = base64_encode($_ENV['CLIENT_ID'].':'.$_ENV['CLIENT_SECRET']);
    }

    public function getToken(): String
    {
        $response = $this->httpClient->request('POST', 'https://accounts.spotify.com/api/token', [
            'headers' => [
                'Authorization' => 'Basic '.$this->key,
                'Content-Type' => 'application/x-www-form-urlencoded'
            ],
            'body' => ['grant_type' => 'client_credentials']
        ]);
        if (200 !== $response->getStatusCode())
        {
            throw new \Exception('El código de respuesta es diferente al esperado.');
        }

        $responseJson = $response->getContent();
        $responseData = json_decode($responseJson);

        return $responseData->access_token;
    }
}