<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use App\Twig\HelloTokenParser;

class HelloTwigExtension extends AbstractExtension
{
    public function getTokenParsers()
    {
        return [
            new HelloTokenParser(),
        ];
    }
}
