<?php

namespace App\Controller;

use LogicException;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends Controller
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        // On récupère les tâches
        $tasks = require_once __DIR__ . '/../../data.php';

        $this->renderView('task/list.html.twig', [
            'tasks' => $tasks
        ]);
    }

    /**
     * @Route("/show/{id}", name="show", requirements={"id": "\d+"})
     */
    public function show(array $routeParams)
    {
        // On récupère les tâches
        $tasks = require_once __DIR__ . '/../../data.php';

        // On récupère l'id (qui est un paramètre de la route)
        $id = $routeParams['id'];

        // Si aucun id n'est passé ou que l'id n'existe pas dans la liste des tâches, on arrête tout !
        if (!$id || !array_key_exists($id, $tasks)) {
            throw new LogicException("La tâche demandée n'existe pas !");
        }

        // Si tout va bien, on récupère la tâche correspondante et on affiche
        $task = $tasks[$id];

        $this->renderView('task/show.html.twig', [
            'task' => $task
        ]);
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
        $this->renderView('task/create.html.twig');
    }
}
