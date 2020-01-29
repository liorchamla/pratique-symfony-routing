<?php

/**
 * TROISIEME PARTIE : LES PARAMETRES DES ROUTES !
 * ----------------------
 * Dans cette section, on explore plus encore les paramètres de routes que nous permet d'utiliser le composant symfony/routing
 * 
 * Pour l'instant, nous avons déjà découvert qu'une route /show/{id} nous permettait de remplacer {id} par ce que l'on souhaite ! On appelle ça
 * un paramètre de route (route parameter).
 * 
 * On a aussi vu qu'en passant /show/110 à l'UrlMatcher, celui ci nous renvoi un tableau associatif qui contient deux informations :
 * - _route : qui contient le nom associé à la route (ici c'est "show")
 * - id : qui contient la valeur donnée au paramètre {id} (ici c'est "110")
 * 
 * DECOUVRONS LES REQUIREMENTS :
 * -------------
 * Le composant nous permet d'ajouter des contraintes sur ces paramètres ! On appelle ça les REQUIREMENTS (obligations).
 * Par exemple, pour l'instant, rien ne nous empêche d'appeler "/show/bonjour" même si on sait très bien qu'on attend plutôt un identifiant
 * numérique et pas un mot ! 
 * 
 * Grâce aux requirements, on va pouvoir faire comprendre à l'UrlMatcher que {id} doit forcément être un nombre ! Appeler /show/bonjour nous
 * donnera donc désormais une erreur "Not Found"
 * 
 * DECOUVRONS AUSSI LES VALEURS PAR DEFAUT :
 * -------------
 * Au cas où vous ne l'auriez pas remarqué, si vous demandez la page /show, on vous dira qu'elle n'existe pas. Car la route que l'on a déclaré
 * est /show/{id}, il faut donc absolument taper quelque chose après /show/CeQueVousVoulez sinon le matcher considérera qu'il n'y a pas de 
 * route existante !
 * 
 * C'est uniquement parce que nous n'avons pas donné de valeur par défaut au paramètre {id} que cela se passe comme ça.
 * 
 * On a créé une nouvelle page (pages/hello.php) qui correspond à la route "/hello/{name}" et on souhaite qu'elle se comporte comme suit
 * - si on appelle /hello/Lior : on voit s'afficher "Hello Lior"
 * - si on appelle /hello : on voit s'afficher "Hello World"
 * 
 * Pour ce faire il faut qu'on fasse comprendre à la route que le paramètre {name} a une valeur par défaut dans le cas où on ne le donne pas
 * 
 * -----------------
 * 
 * Pour suivre toutes les modifications apportées par rapport au code de base, regardez les fichiers suivants :
 * - config/configuration.php : on ajoute un requirement pour la route /show/{id} et on ajoute une route /hello/{name}
 * - pages/hello.php : le script qui dit bonjour :)
 */

use Symfony\Component\Routing\Exception\ResourceNotFoundException;

require_once __DIR__ . '/config/configuration.php';

try {
    $currentRoute = $matcher->match($url);
    // On sait que $currentRoute est un tableau qui contient systématiquement le nom de la route dans l'élément '_route'
    $page = $currentRoute['_route'];

    // On peut maintenant inclure la page
    require_once "pages/$page.php";
} catch (ResourceNotFoundException $e) {
    require 'pages/404.php';
    return;
} catch (Exception $e) {
    // Si on a une autre exception qui a lieu dans notre propre code, on l'affiche
    var_dump($e->getMessage());
}
