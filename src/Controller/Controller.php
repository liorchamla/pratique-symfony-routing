<?php

namespace App\Controller;

use Symfony\Component\Routing\Generator\UrlGenerator;

abstract class Controller
{

    protected $urlGenerator;

    public function __construct(UrlGenerator $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }
}
