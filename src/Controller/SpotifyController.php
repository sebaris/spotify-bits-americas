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
    public function index(SpotifyService $spotify): Response
    {
        $relases = $spotify->getNewReleases();
        return $this->render('spotify/index.html.twig', [
            'controller_name' => 'New Releases',
            'relases' => $relases
        ]);
    }

    #[Route('/artists/{id}', name: 'artists')]
    public function artists(string $id, SpotifyService $spotify): Response
    {
        $artist = $spotify->getArtist($id);
        $albums = $spotify->getAlbumsArtist($id);
        return $this->render('spotify/artists.html.twig', [
            'artist' => $artist,
            'albums' => $albums
        ]);
    }
}
