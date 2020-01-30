<?php

/**
 * SIXIEME PARTIE : GENERATION D'URL DYNAMIQUES AVEC L'URLGENERATOR
 * ----------------------
 * Nous touchons au but ! Il ne nous reste plus qu'à comprendre le concept d'UrlGenerator !
 * 
 * Le composant symfony/routing nous offre aussi un moyen de générer une URL relative à une Route. En effet, il y a un problème récurrent
 * lors du développement et de la vie d'une application lorsqu'on décide de changer une route.
 * 
 * Imaginez que vous ayez une trentaine de page, et qu'une majorité d'entre elles ont un lien qui pointe vers /create.
 * Imaginez maintenant qu'on doive modifier la route pour qu'elle devienne /new
 * 
 * Il vous faut alors aller voir tous les fichiers à la recherche des liens /create pour les remplacer par /new, fastidieux et débile.
 * 
 * L'UrlGenerator vous permet de demander à générer une URL en fonction d'un nom de Route. Prenons le cas de la route qui se nomme "create"
 * - $urlGenerator->generate('create') donnera "/create"
 * - si on change l'URL de la route 'create' en /new, alors $urlGenerator->generate('create') donnera "/new"
 * 
 * On va donc utiliser le plus souvent possible l'UrlGenerator lorsqu'il s'agira d'écrire une URL !
 * 
 * -----------------
 * 
 * Pour suivre toutes les modifications apportées par rapport au code de base, regardez les fichiers suivants :
 * - config/configuration.php : on créé $urlGenerator
 * - index.php : on fait en sore que l'$urlGenerator soit passé systématiquement lorsque l'on instancie le controller
 * - les controllers et les vues : on utilise l'$urlGenerator pour générer les urls
 */

use Symfony\Component\Routing\Exception\ResourceNotFoundException;

require_once __DIR__ . '/config/configuration.php';

try {
    // On trouve la route sur laquelle on se trouve
    $currentRoute = $matcher->match($url);

    // On en déduit la classe et la fonction à appeler
    $controller = $currentRoute['_controller'];
    $className = substr($controller, 0, strpos($controller, '@'));
    $methodName = substr($controller, strpos($controller, '@') + 1);

    // On instancie le controller en lui passant l'UrlGenerator pour qu'il puisse l'utiliser
    $instance = new $className($urlGenerator);
    // On appelle la méthode voulue en lui passant les données relatives à la Route
    $instance->$methodName($currentRoute);
}
// Si aucune route ne corresponde à l'URL actuelle
catch (ResourceNotFoundException $e) {
    require 'pages/404.php';
    return;
}
// Si une route correspond mais qu'une erreur se produit dans le controller 
catch (Exception $e) {
    var_dump($e->getMessage());
}
