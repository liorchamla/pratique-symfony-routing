<?php

/**
 * DERNIERE PARTIE : REFACTORING FINAL (AVEC UN PEU DE TWIG DEDANS)
 * ----------------------
 * 
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

    // On instancie le controller en lui passant le moteur Twig et l'URLGenerator
    $instance = new $className($twig, $urlGenerator);
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
