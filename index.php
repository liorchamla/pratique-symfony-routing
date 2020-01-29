<?php

/**
 * PREMIERE PARTIE : INSTALLATION ET UTILISATION DU COMPOSANT SYMFONY/ROUTING !
 * ----------------------
 * Dans cette section, nous avons installé le composant (composer require symfony/routing) et nous allons l'utiliser pour répondre aux 
 * problèmes posés dans l'application en PHP NATIF. Ceux ci étaient  :
 * ❌ 1) Les URLs n'étaient pas pratique du tout et faisaient intervenir des paramètres GET pour orienter l'application
 * ❌ 2) Les noms des actions étaient liés aux noms des fichiers (/index.php?page=create nous faisait inclure pages/create.php) et changer
 * le nom de l'action impliquait de changer aussi le nom du fichier et inversement ...
 * ❌ 3) Un utilisateur malveillant pouvait inclure des fichiers PHP non prévus (puisqu'on incluait ce qui se trouvait dans le paramètre GET 'page')
 * 
 * Ces 3 problèmes sont résolus via le composant symfony/routing :
 * ✅ 1) Les URLs redeviennent sympas et pratiques (/show/100 au lieu de /index.php?page=show&id=100 !)
 * ✅ 2) On peut tout à fait modifier le nom des fichiers de vues (par exemple pages/create.php en pages/form.php) sans que cela ne change l'URL
 * (qui resterait /create) et inversement (on peut changer /create en /new sans pour autant modifier le fichier pages/create.php en pages/new.php)
 * ✅ 3) L'utilisateur n'a plus aucune influence sur le fichier qu'on inclue ou pas. Il doit forcément se conformer à la collection de Routes
 * qu'on a mis en place sans quoi il verra un 404.
 * 
 * LE FONCTIONNEMENT DU COMPOSANT :
 * -------------
 * En gros, grâce à ce composant, nous allons représenter les URLs existantes pour notre application dans ce qu'on appelle une RouteCollection
 * (oui ... une collection de routes :D).
 * Chaque route porte un nom et représente une URL (par exemple, la route "/" s'appellera "list" et la route "/show/{id}" s'appellera "show")
 * 
 * Nous pouvons alors utiliser un objet UrlMatcher afin de voir :
 * - si l'URL actuellement tapée par le visiteur existe dans notre collection de routes
 * - à quelle route correspond l'URL tapée par le visiteur et y'a-t-il des paramètres à extraire de cette URL (comme dans le cas /show/100 par
 * exemple, où le nombre 100 correspond au paramètre {id}) ?
 * 
 * Pour suivre toutes les modifications apportées par rapport au code de base, regardez les fichiers suivants :
 * - composer.json : on ajoute la dépendance de symfony/routing
 * - index.php : on met en place le nouveau système de routing
 * - pages/create.php : mise à jour des liens
 * - pages/list.php : mise à jour des liens
 * - pages/show.php : modification de la récupération de l'id qui ne passe plus en GET mais directement dans l'URL
 * 
 */

use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

require_once "vendor/autoload.php";

/**
 * LES ROUTES EXISTANTES
 * ---------
 * Afin de pouvoir être sur que le visiteur souhaite voir une page existante, on maintient ici une liste des pages existantes
 * 
 * Avec le composant symfony/routing, on créé une RouteCollection (un ensemble de routes) et l'on explique pour chaque Route ce que l'on
 * attend.
 */
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

// Remplace l'ancien :
// $availablePages =  [
//     'list', 'show', 'create'
// ];

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

/**
 * HOURRA ! FAISONS QUELQUES TESTS :
 * -----------
 * On a construit l'UrlMatcher ! On peut désormais faire un ou deux tests.
 * 
 * L'UrlMatcher a une méthode match(url) qui prend en paramètre une URL et nous dit si il a trouvé une route dans la collection qui y correspond !
 * Il nous renvoie les informations sur cette route sous la forme d'un tableau associatif. Ce tableau associatif contiendra toujours
 * le nom de la route avec la clé "_route" mais peut aussi contenir d'autres informations comme par exemple les paramètres de route.
 *
 * $resultat = $matcher->match('/'); // Voyons ce qu'il nous dit si on appelle "/"
 * var_dump($resultat); // ['_route' => 'list']
 * 
 * $resultat  = $matcher->match('/show/110'); // Voyons ce qu'il nous dit si on appelle '/show/110'
 * var_dump($resultat); // ['_route' => 'show', 'id' => '110']
 * 
 * 
 * SUPER ! CA FONCTIONNE BIEN !
 * ------------
 * On peut désormais passer aux choses sérieuses et demander au matcher de nous donner les informations de la route qui correspond à l'URL
 * réellement tapée par l'utilisateur
 * 
 * ATTENTION :
 * -------
 * Si l'URL tapée par l'utilisateur ne correspond à aucune route connue, il va émettrer un exception de la classe ResourceNotFoundException et
 * il faut donc encadrer cette instruction d'un try/catch.
 * 
 * Si une telle exception apparait, on n'aura qu'à montrer la page 404 ;-)
 */
try {
    $currentRoute = $matcher->match($url);
    // On sait que $currentRoute est un tableau qui contient systématiquement le nom de la route dans l'élément '_route'
    $page = $currentRoute['_route'];
    // Remplace l'ancien code :
    // // Par défaut, la page qu'on voudra voir si on ne précise pas (par exemple sur /index.php) sera "list"
    // $page = 'list';
    // // Si on nous envoi une page en GET, on la prend en compte (exemple : /index.php?page=create)
    // if (isset($_GET['page'])) {
    //     $page = $_GET['page'];
    // }

    // On peut maintenant inclure la page
    require_once "pages/$page.php";
} catch (ResourceNotFoundException $e) {
    require 'pages/404.php';
    return;

    // Remplace l'ancien :
    // if (!in_array($page, $availablePages)) {
    //     require 'pages/404.php';
    //     return;
    // }
}
