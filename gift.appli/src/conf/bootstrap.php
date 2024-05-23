<?php

use \Slim\Factory\AppFactory;

$app = AppFactory::create();

$app->addRoutingMiddleware();
$app->addErrorMiddleware(true, false, false);
$app->setBasePath('/projets/mygiftbox/gift.appli/public');
$app = (require_once __DIR__ . '/routes.php')($app);

return $app;