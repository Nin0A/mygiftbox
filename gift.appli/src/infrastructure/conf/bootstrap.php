<?php

use \Slim\Factory\AppFactory;

$app = AppFactory::create();

$app->addRoutingMiddleware();
$app->addErrorMiddleware(true, false, false);
//$app->setBasePath('/projets/mygiftbox/gift.appli/public');
$app = (require_once __DIR__ . '/routes.php')($app);

$twig = \Slim\Views\Twig::create('../src/app/views/',
                     [/*'cache' => './src/app/views/cache',*/
                      'auto_reload' => true,
                      'strict_variables' => true]);
$app->add(\Slim\Views\TwigMiddleware::create($app, $twig));

return $app;