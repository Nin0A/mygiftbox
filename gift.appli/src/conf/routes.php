<?php
declare(strict_types=1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


//BD
use gift\appli\models\Categorie;
use gift\appli\models\Box;
use Illuminate\Database\Capsule\Manager as DB;
use gift\appli\app\PrestationController;
use gift\appli\app\CreateBoxController;
use gift\appli\app\PostCreateBoxController;
use gift\appli\app\CategoriesController;
/**
 * Initialisation de la base de donnée
 */
$db = new DB();

$db->addConnection(parse_ini_file("gift.db.conf.ini.dist"));

$db->setAsGlobal();
$db->bootEloquent();


return function (\Slim\App $app): \Slim\App {

    //Route par défaut
    $app->get('/', function (Request $request, Response $response, $args) {
        $response->getBody()->write("Hello!");
        return $response;
    });

    
    /**
     * GET /categorie/{id}
     */
    $app->get('/categories[/{id}]', CategoriesController::class);



    /**
     * GET /prestation
     */
    $app->get('/prestation', PrestationController::class);

    /**
     * GET /box/create pour la méthode get on affiche le formulaire
     */
    $app->get('/box/create', CreateBoxController::class);

    /**
     * POST /box/create pour la méthode post on affiche la catégorie correspondante au formulaire rempli
     */

    $app->post('/box/create', PostCreateBoxController::class);

    return $app;
};