<?php

namespace App\Service;

use PhpParser\Node\Expr\Array_;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class SpotifyService
{
    //private $httpClient;
    private $authentication;

    public function __construct(AuthenticationService $authentication)
    {
        //$this->httpClient = $httpClient;
        $this->authentication = $authentication;
    }

    /**
     * Función que consulta en el API los últimos lanzamientos en spotify
     * @return Array
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function getNewReleases(): Array
    {
        $token = $this->authentication->getToken();

        $response = $this->authentication->getHttpClient()->request('GET', $_ENV['URL_BASE_SERVICE'].'/browse/new-releases', [
            'auth_bearer' => $token,
            'query' => [
                'country' => 'CO',
                'limit' => 12,
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

    /**
     * Función que consulta en el API la información de un artista por su ID
     * @param $id
     * @return mixed
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function getArtist($id)
    {
        $token = $this->authentication->getToken();

        $response = $this->authentication->getHttpClient()->request('GET', $_ENV['URL_BASE_SERVICE'].'/artists/'.$id, [
            'auth_bearer' => $token
        ]);
        if (200 !== $response->getStatusCode())
        {
            throw new \Exception('El código de respuesta es diferente al esperado.');
        }

        $responseJson = $response->getContent();
        $responseData = json_decode($responseJson);
        return $responseData;
    }

    /**
     * Función que consulta en el API los albumes asociados a un artista
     * @param $artistId
     * @return mixed
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function getAlbumsArtist($artistId)
    {
        $token = $this->authentication->getToken();

        $response = $this->authentication->getHttpClient()->request('GET', $_ENV['URL_BASE_SERVICE'].'/artists/'.$artistId.'/albums', [
            'auth_bearer' => $token
        ]);
        if (200 !== $response->getStatusCode())
        {
            throw new \Exception('El código de respuesta es diferente al esperado.');
        }

        $responseJson = $response->getContent();
        $responseData = json_decode($responseJson);
        return $responseData->items;
    }

    public function getFirstTrackForAlbum($albumId)
    {
        $token = $this->authentication->getToken();
        $response = $this->authentication->getHttpClient()->request('GET', $_ENV['URL_BASE_SERVICE'].'/albums/'.$albumId.'/tracks', [
            'auth_bearer' => $token,
            'query' => [
                'limit' => 1
            ]
        ]);

        if (200 !== $response->getStatusCode())
        {
            throw new \Exception('El código de respuesta es diferente al esperado.');
        }

        $responseJson = $response->getContent();
        $responseData = json_decode($responseJson);
        return $responseData->items[0];
    }
}