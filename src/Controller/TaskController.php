<?php

namespace App\Controller;

use LogicException;

/**
 * DECOUVRONS UN CONTROLLER :
 * -----------
 * Dans cette classe, j'ai extrait toute la logique qui se trouvait avant dans les fichiers d'affichages (les vues du dossier "pages")
 * 
 * Chaque route devient correspond désormais à une méthode de la classe.
 * 
 * ATTENTION, IMPORTANT : LES PARAMETRES DES ROUTES
 * -----------
 * Une des actions que l'on avait créé (l'action de voir les détails d'un article avec /show/{id}) nécessite d'avoir accès aux paramètres de la
 * route. 
 * 
 * Dans le fichier index.php, on va instancier cette classe et appeler la méthode voulue en lui passant en paramètre les informations relatives
 * à la route que le matcher nous a rendu.
 * 
 * Chaque méthode de nos controller peut donc recevoir ce tableau d'informations sur la route actuelle (et donc les paramètres)
 */
class TaskController
{
    /**
     * LISTE DES TÂCHES :
     * -----------
     * Cette page nous montre la liste des tâches. On l'appelle en tapant l'url /index.php (ou encore /index.php?page=list ou même encore juste /)
     */
    public function index()
    {
        // On récupère les tâches
        $data = require_once 'data.php';

        require_once __DIR__ . '/../../pages/list.php';
    }

    /**
     * LA PAGE DE DETAILS D'UNE TÂCHE
     * -------------
     * Avant, on appelait cette page avec l'URL /index.php?page=show&id=100
     * L'identifiant était donc disponible en GET.
     * 
     * Désormais on l'appelle avec la route /show/{id} (ou {id} sera remplacé par 100, 110 ou n'importe quel identifiant). Ce qui signifie que
     * l'id n'est plus disponible en GET, mais il fait partie des résultat rapportés par l'UrlMatcher lorsqu'il trouve la route correspondante
     * (voir le fichier index.php dans le chapitre sur l'UrlMatcher pour plus de détails).
     * 
     * On devrait donc désormais retrouver l'id dans la variable $routeParams qui nous est passée en paramètres lorsque la méthode est
     * appelée dans index.php !
     */
    public function show(array $routeParams)
    {
        // On appelle la liste des tâches
        $data = require_once "data.php";

        // On récupère l'id (qui est un paramètre de la route)
        $id = $routeParams['id'];

        // Si aucun id n'est passé ou que l'id n'existe pas dans la liste des tâches, on arrête tout !
        if (!$id || !array_key_exists($id, $data)) {
            throw new LogicException("La tâche demandée n'existe pas !");
        }

        // Si tout va bien, on récupère la tâche correspondante et on affiche
        $task = $data[$id];

        require_once __DIR__ . '/../../pages/show.php';
    }

    /**
     * PAGE DE CREATION D'UNE TÂCHE :
     * -------------
     * Cette page peut être appelée de deux façons :
     * - en GET : quand on tape simplement l'adresse /index.php?page=create dans le navigateur, c'est une requête en GET par défaut
     * => Elle affiche simplement le formulaire HTML
     * - en POST : quand on soumet le formulaire, le navigateur va rappeler /index.php?page=create mais cette fois ci en POST
     * => On analyse le $_POST et on traite les données soumises
     */
    public function create()
    {
        // Si la requête arrive en POST, c'est qu'on a soumis le formulaire :
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Traitement à la con (enregistrement en base de données, redirection, envoi d'email, etc)...
            var_dump("Bravo, le formulaire est soumis (TODO : traiter les données)", $_POST);

            // Arrêt du script
            return;
        }

        // Sinon, si on est en GET, on affiche :
        require_once __DIR__ . '/../../pages/create.php';
    }
}
