<?php

namespace App\Twig\Extension;

use App\Twig\Runtime\FrontAppExtensionRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class FrontAppExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/3.x/advanced.html#automatic-escaping
            new TwigFilter('display_short_name', [FrontAppExtensionRuntime::class, 'displayUserShortName']),
        ];
    }
    // public function displayUserShortName($name): string
    // {
    //     $short_name = explode(" ", $name)[0];

              
    //     return ucfirst($short_name);
    // }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('function_name', [FrontAppExtensionRuntime::class, 'doSomething']),
        ];
    }
}
