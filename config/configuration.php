<?php

/**
 * LES ROUTES EXISTANTES
 * ---------
 * Afin de pouvoir être sur que le visiteur souhaite voir une page existante, on maintient ici une liste des pages existantes
 * 
 * Avec le composant symfony/routing, on créé une RouteCollection (un ensemble de routes) et l'on explique pour chaque Route ce que l'on
 * attend.
 */

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

require_once __DIR__ . '/../vendor/autoload.php';

/**
 * DECOUVERTE DE LA CONFIGURATION YAML :
 * -------------
 * Le composant symfony/routing peut lire et interprêter un fichier YAML afin de construire la liste des routes ! Pour ça, il est nécessaire
 * d'installer le composant symfony/yaml (composer require symfony/yaml) qui sert à lire ces fichiers de configuration !
 * 
 * Par ailleurs, on aura aussi besoin du composant symfony/config (composer require symfony/config) qui nous aide notamment à trouver,
 * lire, charger et valider des fichiers de configuration (pas dégueu hein ;))
 * 
 * Allez voir le fichier configuration/routes.yml si ce n'est pas déjà fait, vous verrez qu'il reprend, dans un autre format bien sur,
 * exactement les mêmes notions que ce qu'on a fait en PHP jusqu'à maintenant
 * 
 */

// Création de la collection de routes disponibles pour notre application
// 1) On créé un localisateur de fichiers de configuration qui va chercher dans CE DOSSIER (on est dans le dossier config ;))
$fileLocator = new FileLocator([__DIR__]);
// 2) On créé un chargeur de fichier YAML spécifique aux routes, il se sert du locator pour trouver les fichiers yaml
$routesYamlLoader = new YamlFileLoader($fileLocator);
// 3) On charge la collection de routes du fichier routes.yml
$routesCollection = $routesYamlLoader->load('routes.yml');

// Remplace l'ancien code :
// $routesCollection = new RouteCollection();
// $routesCollection->add('hello', new Route('/hello/{name}', [
//     'name' => 'World',
//     '_controller' => 'App\Controller\HelloController@sayHello'
// ]));
// $routesCollection->add('list', new Route('/', ['_controller' => 'App\Controller\TaskController@index']));
// $routesCollection->add('show', new Route('/show/{id}', ['_controller' => 'App\Controller\TaskController@show'], [
//     'id' => '\d+'
// ]));
// $routesCollection->add('create', new Route('/create', ['_controller' => 'App\Controller\TaskController@create']));

// 1) Construisons le RequestContext :
$url = $_SERVER['PATH_INFO'] ?? '/'; // Si il n'y a rien dans le PATH_INFO c'est qu'on est sur "/"
$method = $_SERVER['REQUEST_METHOD'];
$requestContext = new RequestContext($url, $method);

// 2) Construisons le UrlMatcher :
$matcher = new UrlMatcher($routesCollection, $requestContext);
