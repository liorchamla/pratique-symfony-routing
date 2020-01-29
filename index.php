<?php

/**
 * CINQUIEME PARTIE : CONFIGURATION DES ROUTES AU FORMAT YAML
 * ----------------------
 * Notre code fonctionne désormais très bien avec le composant symfony/routing, on a même réorganisé le tout avec des controllers.
 * 
 * On créé la correspondance entre les controller et les routes grâce au paramètre supplémentaire "_controller" que l'on pose sur chaque
 * route et qu'on décrypte ensuite dans index.php afin d'instancier le bon controller et d'appeler la bonne méthode en lui passant
 * les paramètres de la route.
 * 
 * Ici, nous allons voir comment les composant symfony/yaml et symfony/config vont nous permettre de mettre en place toute la configuration
 * des routes au sein d'un ou plusieurs fichiers YAML. Pour ça il faudra les installer (composer require symfony/yaml symfony/config)
 * 
 * -----------------
 * 
 * Pour suivre toutes les modifications apportées par rapport au code de base, regardez les fichiers suivants :
 * - composer.json : on ajoute les dépendances à symfony/yaml et symfony/config
 * - config/routes.yml : on créé un fichier de configuration yaml et on y reporte tout ce qu'on avait fait en PHP (la RouteCollection)
 * - config/configuration.php : on remplace le code PHP qui créait la collection des routes par le code qui va charger le fichier YAML
 */

use Symfony\Component\Routing\Exception\ResourceNotFoundException;

require_once __DIR__ . '/config/configuration.php';

try {
    $currentRoute = $matcher->match($url);
    // On sait que $currentRoute est un tableau qui contient systématiquement un élément _controller avec une chaine de caractère
    // sous la forme Nom\Complet\De\La\Classe@NomDeLaMethodeAAppeler
    $controller = $currentRoute['_controller'];

    // On prend le contenu de Nom\Complet\De\La\Classe@NomDeLaMethodeAAppeler jusqu'à l'arobase
    // Ca donne donc Nom\Complet\De\La\Classe
    $className = substr($controller, 0, strpos($controller, '@'));

    // On prend le contenu de Nom\Complet\De\La\Classe@NomDeLaMethodeAAppeler à partir de l'arobase
    // Ca donne donc NomDeLaMethodeAAppeler
    $methodName = substr($controller, strpos($controller, '@') + 1);

    // On instancie le controller 
    $instance = new $className();

    // On appelle la méthode en lui passant les paramètres de la route :
    $instance->$methodName($currentRoute);
} catch (ResourceNotFoundException $e) {
    require 'pages/404.php';
    return;
} catch (Exception $e) {
    // Si on a une autre exception qui a lieu dans notre propre code, on l'affiche
    var_dump($e->getMessage());
}
