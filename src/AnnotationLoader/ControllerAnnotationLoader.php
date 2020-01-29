<?php

namespace App\AnnotationLoader;

use Symfony\Component\Routing\Loader\AnnotationClassLoader;

/**
 * LA CLASSE QUI CONFIGURE LES ROUTES
 * ---------
 * Lorsque Doctrine croise une annotation @Route, il va en déduire un objet de la classe Route (ceux qu'on a vu en partie 1) et le configurer.
 * 
 * Néanmoins, on doit expliquer au chargeur d'annotations ce qu'il doit faire ensuite, surtout dans notre cas : les annotations @Route sont 
 * placées au dessus des méthodes des controllers. Revoyons une comparaison entre ce qu'on avait en YAML et ce qu'on a en annotation.
 * 
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
 * Vous pouvez remarquer sur la deuxième façon, avec l'annotation, on retrouve l'URL (/hello/{name}), le nom de la route (hello), les valeurs
 * par défaut (name = World) .. Mais où est donc la donnée _controller (App\Controller\helloController@sayHello) ??
 * 
 * Et bien elle n'est tout simplement pas présente. C'est pourquoi la classe ci-dessous est importante ! Elle nous permet d'ajouter ce genre
 * d'informations perdue à la Route de façon à ce que le UrlMatcher la trouve au moment voulu !
 * 
 * HERITAGE DE LA CLASSE AnnotationClassLoader
 * -----------
 * C'est une classe abstraite qui contient déjà de la logique mais aussi 2 méthodes abstraites qu'on se doit donc d'implémenter :
 * - getDefaultRouteName() qui permet de donner un nom à une route (si on n'a pas précise name="hello" par exemple dans l'annotation)
 * - configureRoute() qui permet de travailler / manipuler la route lorsque Doctrine la trouve
 * 
 * INFORMATION A PRENDRE :
 * ----------
 * Dans le Framework Symfony, tout ceci a déjà été fait pour nous dans le package symfony/framework-bundle (central dans le framework)
 */
class ControllerAnnotationLoader extends AnnotationClassLoader
{
    /**
     * Ne nous intéresse pas pour l'instant
     */
    protected function getDefaultRouteName(\ReflectionClass $class, \ReflectionMethod $method)
    {
        // Attention : vu qu'on ne fait rien ici, n'oubliez surtout pas de mettre un paramètre name="nom_de_la_route" dans l'annotation @Route
    }

    /**
     * FONCTION ULTRA IMPORTANTE POUR NOUS :
     * --------------
     * C'est dans cette fonction qu'on va pouvoir enfin préciser un paramètre _controller comme notre index.php s'attend à le trouver.
     * 
     * Rappelez vous, en YAML et en PHP, on a pu ajouter un paramètre _controller directement au moment de la définition de la Route. Mais
     * avec les annotations, on ne l'a pas écrit ! Il faut donc le déduire et vous savez quoi ? C'est facile :D
     * 
     * Cette fonction reçoit :
     * - $route : la Route que Doctrine a déjà construit avec les infos qu'il a trouvé dans l'annotation
     * - $class : une description de la classe dans laquelle l'annotation a été trouvée
     * - $method : une description de la méthode dans laquelle l'annotation a été trouvée
     * - $annot : l'instance de l'annotation qu'on a trouvé :D
     * 
     * On va donc examiner le paramètre $method pour connaitre le nom de la classe et le nom de la méthode concernée par cette annotation
     * @Route, et on va enrichir $route avec un nouveau paramètre par défaut _controller
     */
    protected function configureRoute(\Symfony\Component\Routing\Route $route, \ReflectionClass $class, \ReflectionMethod $method, $annot)
    {
        // On choppe le nom de la classe sur laquelle l'annotation a été trouvée (ça donne par exemple : App\Controller\HelloController)
        $className = $method->class;

        // On chope le nom de la méthode sur laquelle l'annotation a été trvouée (ça donne par exemple : sayHello)
        $methodName = $method->name;

        // On en déduit la chaine standardisée qu'on utilise partout ailleurs : App\Controller\HelloController@sayHello
        $_controller = "$className@$methodName";

        // On l'ajoute aux défauts de la route
        $route->addDefaults([
            '_controller' => $_controller
        ]);
    }
}
