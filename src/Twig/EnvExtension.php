<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class EnvExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('getenvvar', 'getenv'),
        ];
    }

}
