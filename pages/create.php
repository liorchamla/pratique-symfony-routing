<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Créer une tâche</title>
</head>

<body>
    <h1>Créer une nouvelle tâche</h1>

    <form action="" method="POST">
        <input type="text" name="title" placeholder="Titre de la tâche">
        <input type="text" name="description" placeholder="Description de la tâche">
        <select name="priority">
            <option value="1">Priorité faible</option>
            <option value="2">Priorité moyenne</option>
            <option value="3">Priorité forte</option>
        </select>
        <label>
            <input type="checkbox" name="completed"> Tâche terminée ?
        </label>
        <button type="submit">Enregistrer</button>
    </form>

    <a href="/">Retour à la liste</a>
</body>

</html>