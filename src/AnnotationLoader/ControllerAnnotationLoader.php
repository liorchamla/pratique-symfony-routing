<?php

namespace App\AnnotationLoader;

use Symfony\Component\Routing\Loader\AnnotationClassLoader;

class ControllerAnnotationLoader extends AnnotationClassLoader
{

    protected function getDefaultRouteName(\ReflectionClass $class, \ReflectionMethod $method)
    {
        // Attention : vu qu'on ne fait rien ici, n'oubliez surtout pas de mettre un paramètre name="nom_de_la_route" dans l'annotation @Route
    }

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
