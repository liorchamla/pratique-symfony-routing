<?php

/**
 * DEUXIEME PARTIE : RE ORGANISATION DES FICHIERS !
 * ----------------------
 * Cette section n'apporte pas de révolution, le but est simplement de réorganiser la gestion des routes dans un fichier 
 * config/configuration.php
 * 
 * Pour suivre toutes les modifications apportées par rapport au code de base, regardez les fichiers suivants :
 * - index.php : on met en place le nouveau système de routing via le fichier config/configuration.php
 * - config/configuration.php : on y déplace toute la déclaration des routes et de l'UrlMatcher
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
