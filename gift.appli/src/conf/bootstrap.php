<?php

use \Slim\Factory\AppFactory;
use \Slim\Views\Twig;
use \Slim\Views\TwigMiddleware;

$app = AppFactory::create();

$app->addRoutingMiddleware();
$app->addErrorMiddleware(true, false, false);
$app->setBasePath('/mygiftbox/gift.appli/public');
$app = (require_once __DIR__ . '/routes.php')($app);

// Créer l'objet Twig
$twig = \Slim\Views\Twig::create(__DIR__ . '/../app/views', ['cache' => false, 'auto_reload'=> true]);

// Ajouter le middleware Twig
$app->add(\Slim\Views\TwigMiddleware::create($app, $twig));

return $app;
