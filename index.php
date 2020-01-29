<?php

/**
 * CINQUIEME PARTIE (BIS) : CONFIGURATION DES ROUTES AU FORMAT ANNOTATIONS
 * ----------------------
 * On l'a fait ! On a configuré l'ensemble de nos routes dans un fichier de configuration YAML bien plus simple à écrire et plus lisible 
 * que le code PHP qui correspondait à l'époque.
 * 
 * Et si on essayait une stratégie différente ? 
 * 
 * DECOUVRONS LES ANNOTATIONS :
 * ------------
 * Les annotations, ce sont des informations spécifiques que l'on écrit dans les blocs de documentation au dessus de fonctions ou de classes !
 * Elles permettent de donner des informations SUPPLEMENTAIRES concernant une fonction ou une classe.
 * 
 * Le composant symfony/routing fourni une annotation spécifique qui s'appelle @Route. Elle nous permet d'indiquer au dessus d'une méthode d'un
 * controller que cette méthode correspond à une Route. 
 * 
 * CE QUE CA CHANGE :
 * -------------
 * Prenons l'exemple de la route /hello/{name}, en YAML ça donne ceci (voir le fichier config/routes.yml) :
 * hello:
 *  path: /hello/{name}
 *  defaults: 
 *      name: World
 *      _controller: App\Controller\HelloController@sayHello
 * 
 * Avec les annotations, on écrirait cette configuration directement au dessus de la méthode sayHello() de la class HelloController :
 * 
 * class HelloController {
 *
 *  /**
 *  * @Route("/hello/{name}", name="hello", defaults={"name":"World"})
 *  * /
 *  public function sayHello(array $routeParams) {
 *      // ...
 *  }
 * }
 *
 * Vous voyez ? On retrouve toutes les informations qu'on avait dans le YAML (le nom "hello", l'URL "/hello/{name}", la valeur par défault 
 * pour "name" qui sera "World"). Evidemment, on ne va pas expliquer que _controller est HelloController@sayHello, vu que l'annotation est
 * juste dessus !
 * 
 * LES AVANTAGES :
 * ----------
 * 1) Le code est au même endroit que la configuration (on n'a pas 2 fichiers à gérer, le controller + le fichier de configuration YAML)
 * 2) C'est simple à apprendre et à écrire
 * 3) C'est court :D
 * 
 * ATTENTION, IMPORTANT :
 * ------------
 * Les annotations ne sont pas gérées en PHP NATIF, ce sont juste des indications qu'on donne en commentaire sur une classe ou une fonction.
 * Pour que ces annotations soient prise en compte par le composant symfony/routing, on va avoir besoin de 2 packages :
 * composer require doctrine/annotations doctrine/cache
 * 
 * Et il faudra bien sur modifier la configuration afin de prendre en compte ces annotations.
 * 
 * BONUS :
 * -------------
 * On peut cumuler les deux types de configurations : YAML et Annotations, ça ne pose aucun soucis.
 *  
 * -----------------
 * 
 * Pour suivre toutes les modifications apportées par rapport au code de base, regardez les fichiers suivants :
 * - composer.json : on ajoute les dépendances à doctrine/annotations et doctrine/cache
 * - src/Controller/TaskController.php : on met en place les annotations @Route
 * - config/configuration.php : on ajoute le chargement des routes via les annotations
 * - src/AnnotationLoader/ControllerAnnotationLoader.php : on configure les routes trouvées via annotation pour ajouter le paramètre _controller
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

    // On instancie le controller et on appelle la méthode
    $instance = new $className();
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
