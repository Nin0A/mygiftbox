<?php
declare(strict_types=1);

use gift\api\app\actions\GetCategorieIdAction;
use gift\api\app\actions\GetDefaultAction;
use gift\api\app\actions\GetBoxModifiedAction;
use gift\api\app\actions\GetAllPrestationsAction;



use gift\api\infrastructure\utils\Eloquent;

/* Initialisation de la base de donnée */
Eloquent::init(__DIR__ . '/gift.db.conf.ini.dist');


return function (\Slim\App $app): \Slim\App {

    $app->get('/', GetDefaultAction::class);

    $app->get('/api/categories/{id}', GetCategorieIdAction::class)->setName('categorie_details');;

    $app->get('/api/categories', GetCategorieIdAction::class)->setName('categories_list');;

    $app->get('/api/prestation', GetAllPrestationsAction::class)->setName('prestation');;

    $app->get('/api/box/modified/{id}', GetBoxModifiedAction::class)->setName('box_modified');

    return $app;
};