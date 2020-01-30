<?php

namespace App\Controller;

use LogicException;
use Symfony\Component\Routing\Annotation\Route;

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
class TaskController extends Controller
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        // On récupère les tâches
        $data = require_once 'data.php';

        require_once __DIR__ . '/../../pages/list.php';
    }

    /**
     * @Route("/show/{id}", name="show", requirements={"id": "\d+"})
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
     * @Route("/create", name="create")
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
