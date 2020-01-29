<?php

/**
 * DEMONSTRATION DE PARAMETRES PAR DEFAUT 
 * -----------
 * Pour bien comprendre, imaginons qu'on appelle /hello/Lior
 * $urlMatcher->match($url) va nous renvoyer le tableau suivant :
 * ['_route' => 'hello', 'name' => 'Lior']
 * 
 * Imaginons maintenant qu'on appelle simplement /hello
 * $urlMatcher->match($url) va nous renvoyer le tableau suivant :
 * ['_route' => 'hello', 'name' => 'World']
 * 
 * Car nous avons précisé que par défaut, si rien n'est précisé, le paramètre {name} prend la valeur 'World'
 * 
 * Merveilleux !
 */

$name = $currentRoute['name'];

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Hello <?= $name ?></title>
</head>

<body>
    <h1>Hello <?= $name ?></h1>
    <a href="/">Retourner à l'accueil</a>
</body>

</html>