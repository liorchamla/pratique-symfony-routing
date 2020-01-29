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
$routesCollection->add('list', new Route('/'));

/**
 * DECOUVERTE DES REQUIREMENTS
 * -----------
 * Les requirements (obligations, ou contraintes) sont des règles qui pèses sur les PARAMETRES DE ROUTE (comme {id} dans la route /show/{id})
 * Ils expliquent comment doit se comporter un paramètre. Ici nous souhaitons dire que le paramètre {id} doit forcément être un nombre positif
 * 
 * Ces requirements se font via des expressions régulières que vous créez vous mêmes. Ici nous utilisons \d+ qui veut dire litteralement qu'on
 * veut "un chiffre" (\d), "une fois ou plus" (+), ce qui correspond donc autant à 0, 1, 110 ou 254948.
 * 
 * Désormais, appeler /show/bonjour générera une ResourceNotFoundException car le matcher considère que rien ne correspond à une telle URL
 * Par contre, /show/110 ou /show/12345560 correspondra bien à notre route.
 * 
 * Notez que la contrainte sur le paramètre id peut aussi s'écrire directement dans l'URL comme suit :
 * new Route('/show/{id<\d+>}') => Plus élégant et rapide ;-)
 */
$routesCollection->add('show', new Route('/show/{id}', [], [
    'id' => '\d+'
]));
$routesCollection->add('create', new Route('/create'));

/**
 * DECOUVERTE DES PARAMETRES PAR DEFAUT 
 * ------------
 * Nous allons créer une route correspondant à /hello/{name} mais nous souhaitons que le paramètre {name} soit optionnel !
 * On doit pouvoir appeler autant /hello/Lior que /hello tout court ! 
 * 
 * On l'a vu avec la route /show/{id}, si on n'envoi pas d'id, on a une erreur ResourceNotFoundException ce qui veut dire qu'on DOIT envoyer
 * les paramètres demandés par la route. 
 * 
 * SAUF SI ON ETABLIT DES VALEURS PAR DEFAUT
 * ------------
 * Le seul moyen de faire marcher une route /hello/{name} sans lui envoyer de {name}, c'est de définir pour le paramètre name une valeur par 
 * défaut ! Imaginons qu'on donne la valeur par défaut "World" au paramètre name, quand on appellera /hello sans name, le matcher trouvera
 * pourtant bien la route en question et nous renverra "World" comme valeur pour le paramètre name :D
 */
$routesCollection->add('hello', new Route('/hello/{name}', ['name' => 'World']));

// 1) Construisons le RequestContext :
$url = $_SERVER['PATH_INFO'] ?? '/'; // Si il n'y a rien dans le PATH_INFO c'est qu'on est sur "/"
$method = $_SERVER['REQUEST_METHOD'];
$requestContext = new RequestContext($url, $method);

// 2) Construisons le UrlMatcher :
$matcher = new UrlMatcher($routesCollection, $requestContext);
