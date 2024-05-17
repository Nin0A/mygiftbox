<?php
declare(strict_types=1);



require_once __DIR__ . '/../src/vendor/autoload.php';

/* application boostrap */

$app = AppFactory::create();

$app->addRoutingMiddleware();
$app->addErrorMiddleware(true, false, false);


$app=(require_once __DIR__ . '../src/conf/routes.php')($app);



$app->run();
