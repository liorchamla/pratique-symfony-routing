<?php

namespace App\Controller;

/**
 * DECOUVRONS UN CONTROLLER :
 * -----------
 * Dans cette classe, j'ai extrait toute la logique qui se trouvait avant dans les fichiers d'affichages (les vues du dossier "pages")
 * 
 * Chaque route devient correspond désormais à une méthode de la classe.
 * 
 * ATTENTION, IMPORTANT : LES PARAMETRES DES ROUTES
 * -----------
 * Une des actions que l'on avait créé (l'action de dire bonjour avec /hello/{name}) nécessite d'avoir accès aux paramètres de la
 * route. 
 * 
 * Dans le fichier index.php, on va instancier cette classe et appeler la méthode voulue en lui passant en paramètre les informations relatives
 * à la route que le matcher nous a rendu.
 * 
 * Chaque méthode de nos controller peut donc recevoir ce tableau d'informations sur la route actuelle (et donc les paramètres)
 */
class HelloController extends Controller
{
    public function sayHello(array $routeParams)
    {
        /**
         * $routeParams contient le résultat de l'analyse du matcher ! Donc les paramètres de la route.
         * 
         * Si on appelle /hello/Lior il ressemble à ça :
         * [
         *  '_route' => 'hello', // Nom de la route
         *  '_controller' => 'App\Controller\HelloController@sayHello', // La classe et la méthode à appeler
         *  'name' => 'Lior'
         * ]
         */
        extract($routeParams); // J'extrais les données du tableau $routeParams, et j'obtiens donc une variable $name utilisée
        // dans le fichier d'affichage ci-dessous
        require_once __DIR__ . '/../../pages/hello.php';
    }
}
