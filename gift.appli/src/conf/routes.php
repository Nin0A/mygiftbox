<?php
declare(strict_types=1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

return function (\Slim\App $app): \Slim\App {

    //ajout d'une route
    $app->get('/', function (Request $request, Response $response, $args) {
        $response->getBody()->write("Hello!");
        return $response;
    });

    return $app;

};