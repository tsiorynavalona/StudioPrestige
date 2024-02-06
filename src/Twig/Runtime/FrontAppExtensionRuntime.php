<?php

namespace App\Twig\Runtime;

use Twig\Extension\RuntimeExtensionInterface;

class FrontAppExtensionRuntime implements RuntimeExtensionInterface
{
    public function __construct()
    {
        // Inject dependencies if needed
    }

    public function doSomething($value)
    {
        // ...
    }

    public function displayUserShortName($name): string
    {
        $short_name = explode(" ", $name)[0];

              
        return ucfirst($short_name);
    }

}
