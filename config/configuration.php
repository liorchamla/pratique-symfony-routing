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

$routesCollection = new RouteCollection();

/**
 * CREATION D'UNE ROUTE :
 * -----------
 * Une Route est représentée par un objet de la classe Route et représente une URL. Cette URL peut contenir des partie dynamiques (appelées
 * "route parameters" ou "paramètres de route"), comme par exemple : /show/{id} ou {id} pourrait être remplacé par n'importe quoi, donc
 * - /show/bonjour correspondrait bien à la route /show/{id} ou {id} contiendrait désormais "bonjour"
 * - /show/110 correspondrait aussi à la route /show/{id} ou {id} contiendrait désormais "110"
 * 
 * On aura donc une collection de routes qui représentent chacune une URL donnée.
 * 
 */
$listRoute = new Route('/');
$showRoute = new Route('/show/{id}');
$formRoute = new Route('/create');

/**
 * AJOUT DES ROUTES ET NOMMAGE :
 * ---------
 * On peut désormais ajouter les route à notre collection. C'est l'occasion d'ailleurs de nommer ces routes !
 * Je vais en profiter pour donner à chaque route le nom qui correspond au fichier à inclure (pas folle la guêpe !) :
 * - $listRoute (/) correspond au fichier list.php (sera donc nommée "list")
 * - $showRoute (/show/{id}) correspond au fichier show.php (sera donc nommée "show")
 * - $formRoute (/create) correspond au fichier create.php (sera donc nommée "create")
 * 
 */
$routesCollection->add('list', $listRoute);
$routesCollection->add('show', $showRoute);
$routesCollection->add('create', $formRoute);

/**
 * RENCONTRE AVEC LE FABULEUX URL MATCHER !
 * ----------
 * On a donc une liste de routes bien définies, mais il faut maintenant savoir à quelle route correspond l'URL tapée REELLEMENT par l'utilisateur
 * 
 * Par exemple : si on tape /show/110 dans le navigateur, à quelle Route cela correspond ? L'UrlMatcher est là pour nous aider à le découvrir !
 * 
 * La classe UrlMatcher possède une méthode "match(url)" qui reçoit une url (comme "/show/110") et qui va analyser la RouteCollection pour
 * comprendre à quelle route cela correspond et nous retourner des informations sur cette route (son nom, et plein d'autres choses)
 * 
 * Pour fonctionner, l'UrlMatcher a besoin de deux choses :
 * - La RouteCollection : oui, ça semble logique, pour découvrir quelle route est concernée par une URL donnée, il faut déjà connaitre les
 * routes existantes ...
 * - Le RequestContext : c'est un objet qui représente le contexte de la requête HTTP actuelle (principalement l'URL et la méthode utilisée)
 * 
 * Il faut donc avant tout que l'on découvre l'URL qui a été appelée (on peut le faire via la superglobale $_SERVER['PATH_INFO']) et la 
 * méthode HTTP qui a été utilisée (là aussi, on peut le faire via la superglobale $_SERVER['REQUEST_METHOD'])
 */

// 1) Construisons le RequestContext :
$url = $_SERVER['PATH_INFO'] ?? '/'; // Si il n'y a rien dans le PATH_INFO c'est qu'on est sur "/"
$method = $_SERVER['REQUEST_METHOD'];
$requestContext = new RequestContext($url, $method);

// 2) Construisons le UrlMatcher :
$matcher = new UrlMatcher($routesCollection, $requestContext);
