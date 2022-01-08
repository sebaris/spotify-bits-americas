<?php

namespace App\Service;

use PhpParser\Node\Expr\Array_;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class SpotifyService
{
    private $authentication;
    private $baseUrlApi;

    public function __construct(ParameterBagInterface $params, AuthenticationService $authentication)
    {
        $this->baseUrlApi = $params->get('api.url_base');
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
        $token = $this->authentication->validateToken();

        $response = $this->authentication->getHttpClient()->request('GET', $this->baseUrlApi.'/browse/new-releases', [
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
        $token = $this->authentication->validateToken();

        $response = $this->authentication->getHttpClient()->request('GET', $this->baseUrlApi.'/artists/'.$id, [
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
        $token = $this->authentication->validateToken();

        $response = $this->authentication->getHttpClient()->request('GET', $this->baseUrlApi.'/artists/'.$artistId.'/albums', [
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

    /**
     * Función que consulta las canciones de un album y retorna la primera de ellas
     * @param $albumId
     * @return mixed
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function getFirstTrackForAlbum($albumId)
    {
        $token = $this->authentication->validateToken();
        $response = $this->authentication->getHttpClient()->request('GET', $this->baseUrlApi.'/albums/'.$albumId.'/tracks', [
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