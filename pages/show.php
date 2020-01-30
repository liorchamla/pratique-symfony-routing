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
    <a href="/">Retour à la liste</a> ou <a href="<?= $this->urlGenerator->generate('create') ?>">Créer une autre tâche</a>
</body>

</html>