<?php
declare(strict_types=1);

use gift\appli\app\actions\GetCategorieIdAction;
use gift\appli\app\actions\PostBoxCreateAction;
use gift\appli\app\actions\GetBoxCreateAction;
use gift\appli\app\actions\GetDefaultAction;
use gift\appli\app\actions\GetPrestationAction;
use gift\appli\models\Categorie;
use gift\appli\utils\Eloquent;
use Slim\Exception\HttpBadRequestException;
use Slim\Views\Twig;
/* Initialisation de la base de donnée */
Eloquent::init(__DIR__ . '/gift.db.conf.ini.dist');


return function (\Slim\App $app): \Slim\App {

    //Route par défaut
    $app->get('/', GetDefaultAction::class);
    
    /**
     * GET /categorie/{id}
     */

    $app->get('/categories/{id}', function ($request, $response, $args) {
        $category = Categorie::find($args['id']);

        if (!$category) {
            throw new HttpBadRequestException($request, "Categories not found");
        }

        $view = Twig::fromRequest($request);
        return $view->render($response, 'categories.twig', ['categorie' => $category]);
    });

     /**
     * GET /categorie/
     */
    $app->get('/categories[/]', GetCategorieIdAction::class);

    /**
     * GET /prestation
     */

    $app->get('/prestation', GetPrestationAction::class);

    /**
     * GET /box/create pour la méthode get on affiche le formulaire
     */
    $app->get('/box/create', GetBoxCreateAction::class);


    /**
     * POST /box/create pour la méthode post on affiche la catégorie correspondante au formulaire rempli
     */
    $app->post('/box/create', PostBoxCreateAction::class);

    return $app;
};