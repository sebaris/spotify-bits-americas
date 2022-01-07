<?php

namespace App\Controller;

use App\Service\AuthenticationService;
use App\Service\SpotifyService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SpotifyController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(AuthenticationService $authentication): Response
    {
        $token = $authentication->getToken();
        var_dump($token);
        return $this->render('spotify/index.html.twig', [
            'controller_name' => 'SpotifyController',
        ]);
    }
}
