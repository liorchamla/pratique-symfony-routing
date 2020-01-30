<?php

namespace App\Controller;

use Symfony\Component\Routing\Generator\UrlGenerator;
use Twig\Environment;

abstract class Controller
{
    protected $twig;
    protected $urlGenerator;

    public function __construct(Environment $twig, UrlGenerator $generator)
    {
        $this->urlGenerator = $generator;
        $this->twig = $twig;
    }

    protected function renderView(string $path, array $variables = [])
    {
        $variables['urlGenerator'] = $this->urlGenerator;

        $this->twig->display($path, $variables);
    }
}
