<?php
declare(strict_types=1);

use gift\appli\app\actions\GetCategorieIdAction;
use gift\appli\app\actions\PostBoxCreateAction;
use gift\appli\app\actions\GetBoxCreateAction;
use gift\appli\app\actions\GetDefaultAction;
use gift\appli\app\actions\GetPrestationAction;
use gift\appli\infrastructure\utils\Eloquent;

/* Initialisation de la base de donnée */
Eloquent::init(__DIR__ . '/gift.db.conf.ini.dist');


return function (\Slim\App $app): \Slim\App {

    //Route par défaut
    $app->get('/', GetDefaultAction::class);
    
    /**
     * GET /categorie/{id}
     */

    $app->get('/categories/{id}', GetCategorieIdAction::class)->setName('categorie_details');;

     /**
     * GET /categorie/
     */
    $app->get('/categories[/]', GetCategorieIdAction::class)->setName('categories_list');;

    /**
     * GET /prestation
     */

    $app->get('/prestation', GetPrestationAction::class)->setName('prestation');;

    /**
     * GET /box/create pour la méthode get on affiche le formulaire
     */
    $app->get('/box/create', GetBoxCreateAction::class)->setName('box_creation');;


    /**
     * POST /box/create pour la méthode post on affiche la catégorie correspondante au formulaire rempli
     */
    $app->post('/box/create', PostBoxCreateAction::class);


    /**
     * GET /sort pour la méthode get on affiche le formulaire
     */
    $app->get('/sortpresta', GetSortPrestation::class)->setName('sort');;


    /**
     * POST sort pour la méthode post on affiche la catégorie correspondante au formulaire rempli
     */
    $app->post('/sortpresta', PostSortPrestation::class);

    return $app;
};