<?php

/**
 * LES ROUTES EXISTANTES
 * ---------
 * Afin de pouvoir être sur que le visiteur souhaite voir une page existante, on maintient ici une liste des pages existantes
 * 
 * Avec le composant symfony/routing, on créé une RouteCollection (un ensemble de routes) et l'on explique pour chaque Route ce que l'on
 * attend.
 */

use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

require_once __DIR__ . '/../vendor/autoload.php';

// Création de la collection de routes disponibles pour notre application
$routesCollection = new RouteCollection();

/**
 * DECOUVERTE DES PARAMETRES SUPPLEMENTAIRES :
 * ---------------
 * Dans la section précédente, on a vu qu'on pouvait définir des valeurs par défaut pour des paramètres de route. MAIS ON PEUT FAIRE PLUS 
 * ENCORE ! On peut définir des valeurs par défaut SUPPLEMENTAIRES, on n'est pas limité aux paramètres de la route.
 * 
 * Ici par exemple, j'ajoute un paramètre que j'appelle '_controller' et qui contient une chaine de caractères représentant la classe que je
 * veux instancier et la méthode que je veux appeler dessus !
 * 
 * Désormais, en tapant /hello/Lior, l'urlMatcher va me renvoyer le tableau suivant :
 * ['_route' => 'hello', 'name' => 'Lior', '_controller' => 'App\Controller\HelloController@sayHello']
 * 
 * Charge à moi ensuite de travailler sur la chaine _controller pour instancier la bonne classe et appeler la méthode sayHello() dessus !
 * Magique.
 * 
 * Bien sur, on fait ça pour toutes nos routes :)
 * 
 * IMPORTANT ET A NOTER :
 * -----------
 * J'ai CHOISI d'appeler cette information '_controller' parce que c'est ce qu'on voit le plus souvent (et que vous retrouvez dans le framework
 * Symfony) mais j'aurai pu choisir de l'appeler 'cocoLAsticot', C'EST ARBITRAIRE !
 */
$routesCollection->add('hello', new Route('/hello/{name}', [
    'name' => 'World',
    '_controller' => 'App\Controller\HelloController@sayHello'
]));

$routesCollection->add('list', new Route('/', ['_controller' => 'App\Controller\TaskController@index']));
$routesCollection->add('show', new Route('/show/{id}', ['_controller' => 'App\Controller\TaskController@show'], [
    'id' => '\d+'
]));
$routesCollection->add('create', new Route('/create', ['_controller' => 'App\Controller\TaskController@create']));

// 1) Construisons le RequestContext :
$url = $_SERVER['PATH_INFO'] ?? '/'; // Si il n'y a rien dans le PATH_INFO c'est qu'on est sur "/"
$method = $_SERVER['REQUEST_METHOD'];
$requestContext = new RequestContext($url, $method);

// 2) Construisons le UrlMatcher :
$matcher = new UrlMatcher($routesCollection, $requestContext);
