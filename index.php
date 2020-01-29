<?php

/**
 * QUATRIEME PARTIE : LES PARAMETRES SUPPLEMENTAIRES DANS UNE ROUTE (ET UN PEU DE POO) !
 * ----------------------
 * Dans la dernière section, nous avons découverts que nous pouvions travailler sur les paramètres des routes :
 * 1) Les requirements nous permettent de leur donner un cadre et des contraintes (exemple : /show/{id} avec un id numérique UNIQUEMENT)
 * 2) Les valeurs par défaut nous permettent de ne pas renseigner les paramètres obligatoirement dans l'URL (exemple : qu'on puisse appeler
 * /hello/Lior ou même /hello (sans donnée pour le paramètre {name}))
 * 
 * On peut donc désormais comprendre une autre notion essentielle : ON PEUT ENRICHIR LES VALEURS PAR DEFAUT AVEC DES PARAMETRES PERSONNALISES.
 * 
 * Prenons un exemple simple : imaginons la route /hello/{name}
 * - Elle ne contient qu'un seul paramètre : {name}
 * - On peut donc lui donner une valeur par défaut : ['name' => 'World'] qui veut dire que si on ne donne rien dans l'URL (/hello), alors name 
 * vaudra World. Si on appelle /hello, le matcher nous rendra le tableau suivant :
 * [
 *  '_route' => 'hello', // Nom de la route trouvée
 *  'name' => 'World' // Valeur du paramètre {name}
 * ]
 * - Mais on peut aussi donner des valeurs par défaut pour des paramètres qui ne sont pas VISIBLES : ['name' => 'World', 'autre' => 'toto']
 * Si on appelle le matcher avec /hello il nous rendra le tableau suivant :
 * [
 *  '_route' => 'hello',
 *  'name' => 'World',
 *  'autre' => 'toto' // Le matcher a inclus la valeur par défaut qu'on avait défini dans la route !
 * ]
 * 
 * BON, ET ALORS ? 
 * -------------
 * Sans en avoir l'air, c'est une fonctionnalité très puissante qui nous est proposée ici, puisqu'elle permet de donner les informations
 * supplémentaires que l'on souhaite pour chaque route ! Et si nous nous en servions pour expliquer à quelle action nous voulons associer
 * chaque route ? ;-)
 * 
 * INTRODUCTION DES CONTROLLERS :
 * --------------
 * Nous avons ajouté un dossier src/Controller qui contient deux nouvelles classes :
 * - HelloController (qui va gérer la route /hello/{name})
 * - TaskController (qui va gérer les routes /, /create et /show/{id})
 * 
 * Jusqu'à présent, on se servait simplement du nom des routes pour découvrir le fichier PHP à inclure. C'était simple et ça faisait bien
 * le boulot, mais ça demandait un fichier PHP par action de notre site. Pas pratique si notre site comporte des dizaines d'actions possibles.
 * 
 * J'ai donc créé deux classes qui concentrent en tout 4 actions (oui, donc 2 fichiers au lieu de 4 fichiers pour le même nombre d'actions)
 * Mais comment faire lorsqu'un URL est tapée pour savoir quelle classe doit s'en charger, et quelle méthode on doit appeler ?
 * 
 * FACILE : On ajoute à chaque route une valeur par défaut qui explique qui doit gérer la route ! Ensuite, le matcher nous rendra après son
 * analyse de l'URL tous les paramètres de la route DONT celui qui nous dit qui est la classe qui gère et quelle méthode on doit appeler !
 * 
 * UN CONSEIL :
 * ----------
 * Pour ne pas trop vous prendre la tête au départ, concentrez vous vraiment sur l'action /hello/{name} et le controller HelloController
 * Si vous comprenez cette partie simple, alors vous pourrez comprendre plus facilement ce qui se passe sur le controller TaskController
 * 
 * Les fichiers à vraiment bien examiner ici sont :
 * - config/configuration.php où on indique à chaque route quelle est la classe et la méthode qu'il faudra appeler pour la gérer
 * - index.php où on instancie la classe correspondante à une route et sur laquelle on appelle la méthode voulue en lui passant
 * les paramètres de la route pour qu'elle puisse les utiliser si elle veut.
 * 
 * -----------------
 * 
 * Pour suivre toutes les modifications apportées par rapport au code de base, regardez les fichiers suivants :
 * - composer.json : on ajoute une politique d'autoloading pour qu'il sache trouver où sont nos classes
 * - config/configuration.php : on ajoute pour chaque route un paramètre par défaut qui nous indique la classe à instancier et la méthode à 
 * appeler
 * - index.php : on met en place un algorithme pour instancier pour chaque route le bon controller et appeler la bonne méthode
 * - src/Controller/TaskController.php : une classe qui reprend les actions concernant les tâches (liste, détails et formulaire)
 * - src/Controller/HelloController.php : une classe qui reprend l'action hello (dire bonjour :D)
 * - pages/hello.php : on ne garde que le html
 * - pages/list.php : on ne garde que le html
 * - pages/create.php : on ne garde que le html
 * - pages/show.php : on ne garde que le html
 *
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
