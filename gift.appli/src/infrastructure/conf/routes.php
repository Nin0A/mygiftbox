<?php
declare(strict_types=1);

use gift\appli\app\actions\GetCategorieIdAction;
use gift\appli\app\actions\GetLoginAction;
use gift\appli\app\actions\PostLoginAction;
use gift\appli\app\actions\PostBoxCreateAction;
use gift\appli\app\actions\GetBoxCreateAction;
use gift\appli\app\actions\PostCategorieCreateAction;
use gift\appli\app\actions\GetCategorieCreateAction;
use gift\appli\app\actions\GetDefaultAction;
use gift\appli\app\actions\GetPrestationAction;
use gift\appli\app\actions\GetBoxModifiedAction;
use gift\appli\app\actions\PostBoxModifiedAction;
use gift\appli\app\actions\GetRegisterAction;
use gift\appli\app\actions\PostRegisterAction;


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
    $app->get('/categories', GetCategorieIdAction::class)->setName('categories_list');;

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
     * GET /box/create pour la méthode get on affiche le formulaire
     */
    $app->get('/box/modified/{id}', GetBoxModifiedAction::class)->setName('box_modified');


    /**
     * POST /box/create pour la méthode post on affiche la catégorie correspondante au formulaire rempli
     */
    $app->post('/box/modified/{id}', PostBoxModifiedAction::class)->setName('post_box_modified');


    /**
     * GET /categories/create pour la méthode get on affiche le formulaire
     */
    $app->get('/categorie/create', GetCategorieCreateAction::class)->setName('categorie_creation');


    /**
     * POST /categories/create pour la méthode post on affiche la catégorie correspondante au formulaire rempli
     */
    $app->post('/categorie/create', PostCategorieCreateAction::class);

    $app->get('/account/login', GetLoginAction::class)->setName('account_login');

    $app->post('/account/login', PostLoginAction::class);

    $app->get('/account/register', GetRegisterAction::class)->setName('account_register');

    $app->post('/account/register', PostRegisterAction::class);





    return $app;
};