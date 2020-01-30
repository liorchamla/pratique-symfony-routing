<?php

/**
 * LES ROUTES EXISTANTES
 * ---------
 * Afin de pouvoir être sur que le visiteur souhaite voir une page existante, on maintient ici une liste des pages existantes
 * 
 * Avec le composant symfony/routing, on créé une RouteCollection (un ensemble de routes) et l'on explique pour chaque Route ce que l'on
 * attend.
 */

use App\AnnotationLoader\ControllerAnnotationLoader;
use Symfony\Component\Routing\Route;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\Loader\AnnotationFileLoader;
use Symfony\Component\Routing\Loader\AnnotationClassLoader;
use Symfony\Component\Routing\Loader\AnnotationDirectoryLoader;
use Symfony\Component\Routing\Router;

require_once __DIR__ . '/../vendor/autoload.php';

/**
 * DECOUVERTE DE LA CONFIGURATION YAML :
 * -------------
 * Le composant symfony/routing peut lire et interprêter un fichier YAML afin de construire la liste des routes ! Pour ça, il est nécessaire
 * d'installer le composant symfony/yaml (composer require symfony/yaml) qui sert à lire ces fichiers de configuration !
 * 
 * Par ailleurs, on aura aussi besoin du composant symfony/config (composer require symfony/config) qui nous aide notamment à trouver,
 * lire, charger et valider des fichiers de configuration (pas dégueu hein ;))
 * 
 * Allez voir le fichier configuration/routes.yml si ce n'est pas déjà fait, vous verrez qu'il reprend, dans un autre format bien sur,
 * exactement les mêmes notions que ce qu'on a fait en PHP jusqu'à maintenant
 * 
 */

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
 * Pour pouvoir charger la configuration à partir des annotations qui se trouvent dans nos classes, on a besoin d'un lecteur d'annotations.
 * 
 * La composant symfony/routing nous prodigue ce lecteur : la classe AnnotationDirectoryLoader qui va pouvoir lire toutes les classes d'un
 * dossier et en extraire les annotations (dans notre cas, les annotations @Route qui se trouvent sur les fonctions)
 * 
 * Quand Doctrine va voir une annotation @Route, il va trouver la classe PHP qui correspond à cette annotation et la traduire en PHP natif.
 * 
 * On devra ensuite travailler sur l'objet que Doctrine aura déduit de l'annotation et .. BOUM : on obtient un objet Route comme ceux qu'on
 * avait lorsqu'on construisait notre collection (voir la partie 1).
 * 
 * ATTENTION :
 * ---------
 * Quand Doctrine voit une annotation @Route, il cherche la classe qui correspond. Par défaut, il ne la trouvera pas, il faut donc lui 
 * donner accès à l'autoloader afin qu'il puisse la retrouver 
 */
$loader = require __DIR__ . '/../vendor/autoload.php';

AnnotationRegistry::registerLoader([$loader, 'loadClass']);

/**
 * CREONS LE LOADER D'ANNOTATIONS :
 * ------------
 * Le composant nous fournit une classe AnnotationDirectoryLoader qui permet d'aller inspecter tous les fichiers dans un dossier particulier
 * et en extraire toutes les annotations !
 * 
 * Pour le construire, on lui passe un FileLocator (qui permet de rechercher des fichiers dans un chemin spécifié) et un autre objet qui doit
 * hériter de la classe abstraite AnnotationClassLoader.
 * 
 * Le but de cet objet sera de configurer l'objet Route que doctrine aura créé en voyant une annotation @Route
 * 
 * ATTENTION :
 * ------------
 * Le composant ne fournit pas d'objet particulier pour gérer cette partie. C'est donc à nous de créer une implémentation qui nous convient
 * en créant une classe qui hérite de AnnotationClassLoader et qui redéfinit les fonctions nécessaires.
 * 
 * J'ai fait cela dans src/AnnotationLoader/ControllerAnnotationLoader.php pour donner un exemple
 */
$loader = new AnnotationDirectoryLoader(
    new FileLocator([__DIR__ . '/../src/Controller']),
    new ControllerAnnotationLoader(new AnnotationReader())
);

// Création de la collection de routes disponibles pour notre application en chargeant les annotations
$annotationsRoutesCollection = $loader->load(__DIR__ . '/../src/Controller');
// Fusion avec ce qui vient du YAML :
$routesCollection->addCollection($annotationsRoutesCollection);


// 1) Construisons le RequestContext :
$url = $_SERVER['PATH_INFO'] ?? '/'; // Si il n'y a rien dans le PATH_INFO c'est qu'on est sur "/"
$method = $_SERVER['REQUEST_METHOD'];
$requestContext = new RequestContext($url, $method);

// 2) Construisons le UrlMatcher :
$matcher = new UrlMatcher($routesCollection, $requestContext);

/**
 * DECOUVERTE DE L'URLGENERATOR : 
 * -----------
 * Le composant symfony/routing nous offre aussi un moyen de générer une URL relative à une Route. En effet, il y a un problème récurrent
 * lors du développement et de la vie d'une application lorsqu'on décide de changer une route.
 * 
 * Imaginez que vous ayez une trentaine de page, et qu'une majorité d'entre elles ont un lien qui pointe vers /create.
 * Imaginez maintenant qu'on doive modifier la route pour qu'elle devienne /new
 * 
 * Il vous faut alors aller voir tous les fichiers à la recherche des liens /create pour les remplacer par /new, fastidieux et débile.
 * 
 * L'UrlGenerator vous permet de demander à générer une URL en fonction d'un nom de Route. Prenons le cas de la route qui se nomme "create"
 * - $urlGenerator->generate('create') donnera "/create"
 * - si on change l'URL de la route 'create' en /new, alors $urlGenerator->generate('create') donnera "/new"
 * 
 * On va donc utiliser le plus souvent possible l'UrlGenerator lorsqu'il s'agira d'écrire une URL !
 * 
 * IMPORTANT ET A SAVOIR :
 * ------------
 * Notez que le composant vous offre aussi une classe Router qui combine à la fois les fonctionnalités du UrlMatcher et du UrlGenerator !
 */
$urlGenerator = new UrlGenerator($routesCollection, new RequestContext());
