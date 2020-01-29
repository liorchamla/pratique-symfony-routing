<?php

/**
 * LES ROUTES EXISTANTES
 * ---------
 * Afin de pouvoir être sur que le visiteur souhaite voir une page existante, on maintient ici une liste des pages existantes
 * 
 * Avec le composant symfony/routing, on créé une RouteCollection (un ensemble de routes) et l'on explique pour chaque Route ce que l'on
 * attend.
 */

use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

require_once __DIR__ . '/../vendor/autoload.php';

$routesCollection = new RouteCollection();

/**
 * CREATION D'UNE ROUTE :
 * -----------
 * Une Route est représentée par un objet de la classe Route et représente une URL. Cette URL peut contenir des partie dynamiques (appelées
 * "route parameters" ou "paramètres de route"), comme par exemple : /show/{id} ou {id} pourrait être remplacé par n'importe quoi, donc
 * - /show/bonjour correspondrait bien à la route /show/{id} ou {id} contiendrait désormais "bonjour"
 * - /show/110 correspondrait aussi à la route /show/{id} ou {id} contiendrait désormais "110"
 * 
 * On aura donc une collection de routes qui représentent chacune une URL donnée.
 * 
 */
$listRoute = new Route('/');
$showRoute = new Route('/show/{id}');
$formRoute = new Route('/create');

/**
 * AJOUT DES ROUTES ET NOMMAGE :
 * ---------
 * On peut désormais ajouter les route à notre collection. C'est l'occasion d'ailleurs de nommer ces routes !
 * Je vais en profiter pour donner à chaque route le nom qui correspond au fichier à inclure (pas folle la guêpe !) :
 * - $listRoute (/) correspond au fichier list.php (sera donc nommée "list")
 * - $showRoute (/show/{id}) correspond au fichier show.php (sera donc nommée "show")
 * - $formRoute (/create) correspond au fichier create.php (sera donc nommée "create")
 * 
 */
$routesCollection->add('list', $listRoute);
$routesCollection->add('show', $showRoute);
$routesCollection->add('create', $formRoute);
