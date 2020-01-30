<?php

namespace App\Controller;

class HelloController extends Controller
{
    public function sayHello(array $routeParams)
    {
        $this->renderView('hello/hello.html.twig', [
            'name' => $routeParams['name']
        ]);
    }
}
