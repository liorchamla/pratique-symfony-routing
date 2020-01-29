<?php

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
 * On devrait donc désormais retrouver l'id dans la variable $currentRoute['id'] !
 */

// On appelle la liste des tâches
$data = require_once "data.php";

// On récupère l'id (qui est un paramètre de la route)
$id = $currentRoute['id'];
// Remplace l'ancien code :
// $id = null;
// if (isset($_GET['id'])) {
//     $id = $_GET['id'];
// }

// Si aucun id n'est passé ou que l'id n'existe pas dans la liste des tâches, on arrête tout !
if (!$id || !array_key_exists($id, $data)) {
    throw new Exception("La tâche demandée n'existe pas !");
}

// Si tout va bien, on récupère la tâche correspondante et on affiche
$task = $data[$id];

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Détails de la tâche <?= $task['title'] ?></title>
</head>

<body>
    <h1>Détails de <em><?= $task['title'] ?></em></h1>
    <p><?= $task['description'] ?></p>
    <p>
        La tâche est <strong><?= $task['completed'] ? "complétée" : "encore à faire" ?> !</strong>
    </p>
    <a href="/">Retour à la liste</a> ou <a href="/create">Créer une autre tâche</a>
</body>

</html>