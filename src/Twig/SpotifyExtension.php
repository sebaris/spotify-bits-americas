<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class SpotifyExtension extends AbstractExtension
{

    /**
     * {@inheritdoc}
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('fisrtTrack', [SpotifyRuntime::class, 'getFirstTrack']),
        ];
    }
}