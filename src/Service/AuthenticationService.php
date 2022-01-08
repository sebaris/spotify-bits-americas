<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class AuthenticationService
{
    private $httpClient;
    private $requestStack;
    private $key;

    public function __construct(HttpClientInterface $httpClient, RequestStack $requestStack)
    {
        $this->httpClient = $httpClient;
        $this->requestStack = $requestStack;
        $this->key = base64_encode($_ENV['CLIENT_ID'].':'.$_ENV['CLIENT_SECRET']);
    }

    /**
     * Getter HttpClient
     * @return HttpClientInterface
     */
    public function getHttpClient()
    {
        return $this->httpClient;
    }

    /**
     * Función que valida las credenciales de acceso y optiene el token de consulta
     * @return String
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    private function getToken(): String
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

        //Se guarda en sesion los datos
        $this->saveSessionToken($responseData);

        return $responseData->access_token;
    }

    /**
     * Función que almacena en session las variables de token
     * @param $info
     */
    private function saveSessionToken($info)
    {
        $session = $this->requestStack->getSession();
        $session->set('time_token', strtotime('now'));
        $session->set('token', $info->access_token);
        $session->set('expire', $info->expires_in);
    }

    /**
     * Funciín que valida la existencia y tiempo de expiración de un token para retornar el exitente o consultar uno nuevo
     * @return mixed|String
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function validateToken()
    {
        $session = $this->requestStack->getSession();
        $token = $session->get('token', null);
        if (isset($token))
        {
            $timeToken = $session->get('time_token');
            $expire = $session->get('expire');
            $expireTime = strtotime(' +'.$expire.' seconds', $timeToken);
            if (strtotime('now') <= $expireTime)
            {
                return $token;
            }
        }
        return $this->getToken();
    }
}