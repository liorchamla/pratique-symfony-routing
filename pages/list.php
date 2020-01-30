<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Liste des tâches</title>
</head>

<body>
    <h1>Liste des tâches</h1>

    <a href="<?= $this->urlGenerator->generate('create') ?>">Créer une tâche</a>

    <?php foreach ($data as $id => $task) : ?>
        <h2><?= $task['title'] ?> (<?= $task['completed'] ? "Complête" : "Incomplête" ?>)</h2>
        <small>Priorité : <?= $task['priority'] ?></small><br>
        <a href="<?= $this->urlGenerator->generate('show', ['id' => $id]) ?>">En savoir plus</a>
    <?php endforeach ?>
</body>

</html>