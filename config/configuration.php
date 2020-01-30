<?php

use App\AnnotationLoader\ControllerAnnotationLoader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Routing\RequestContext;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\Loader\AnnotationDirectoryLoader;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

require_once __DIR__ . '/../vendor/autoload.php';

/**
 * CREATION DU MOTEUR TWIG
 * --------
 */
$twig = new Environment(new FilesystemLoader([__DIR__ . '/../templates']));


/**
 * CHARGEMENT DES ROUTES DU FICHIER YAML
 * --------
 */

// 1) On créé un localisateur de fichiers de configuration qui va chercher dans CE DOSSIER (on est dans le dossier config ;))
$fileLocator = new FileLocator([__DIR__]);
// 2) On créé un chargeur de fichier YAML spécifique aux routes, il se sert du locator pour trouver les fichiers yaml
$routesYamlLoader = new YamlFileLoader($fileLocator);
// 3) On charge la collection de routes du fichier routes.yml
$routesCollection = $routesYamlLoader->load('routes.yml');

/**
 * CHARGEMENT DES ROUTES A PARTIR DES ANNOTATIONS
 * --------
 */

// Autoloading pour Doctrine
$loader = require __DIR__ . '/../vendor/autoload.php';
AnnotationRegistry::registerLoader([$loader, 'loadClass']);

// Chargement des routes d'annotations
$annotationsLoader = new AnnotationDirectoryLoader(
    new FileLocator([__DIR__ . '/../src/Controller']),
    new ControllerAnnotationLoader(new AnnotationReader())
);
$annotationsRoutesCollection = $annotationsLoader->load(__DIR__ . '/../src/Controller');

// Fusion avec ce qui vient du YAML :
$routesCollection->addCollection($annotationsRoutesCollection);

// Construction de l'UrlMatcher
$url = $_SERVER['PATH_INFO'] ?? '/'; // Si il n'y a rien dans le PATH_INFO c'est qu'on est sur "/"
$method = $_SERVER['REQUEST_METHOD'];
$requestContext = new RequestContext($url, $method);
$matcher = new UrlMatcher($routesCollection, $requestContext);

// Construction de l'UrlGenerator
$urlGenerator = new UrlGenerator($routesCollection, new RequestContext(''));
