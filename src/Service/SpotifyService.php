<?php

namespace App\Service;

use PhpParser\Node\Expr\Array_;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class SpotifyService
{
    private $httpClient;
    private $authentication;

    public function __construct(HttpClientInterface $httpClient, AuthenticationService $authentication)
    {
        $this->httpClient = $httpClient;
        $this->authentication = $authentication;
    }

    public function getNewReleases(): Array
    {
        $token = $this->authentication->getToken();

        $response = $this->httpClient->request('GET', 'https://api.spotify.com/v1/browse/new-releases', [
            'auth_bearer' => $token,
            'query' => [
                'limit' => 3,
            ],
        ]);
        if (200 !== $response->getStatusCode())
        {
            throw new \Exception('El código de respuesta es diferente al esperado.');
        }

        $responseJson = $response->getContent();
        $responseData = json_decode($responseJson);
        return $responseData->albums->items;
    }
}