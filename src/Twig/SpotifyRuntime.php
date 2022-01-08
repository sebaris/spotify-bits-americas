<?php

namespace App\Twig;

use App\Service\SpotifyService;
use Twig\Extension\RuntimeExtensionInterface;

class SpotifyRuntime implements RuntimeExtensionInterface
{
    private $spotifyService;

    public function __construct(SpotifyService $spotifyService)
    {
        $this->spotifyService = $spotifyService;
    }

    public function getFirstTrack(string $albumId): string
    {
        $infoTrack = $this->spotifyService->getFirstTrackForAlbum($albumId);
        return $infoTrack->name;
    }
}