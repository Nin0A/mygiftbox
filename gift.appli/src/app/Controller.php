<?php

namespace gift\appli\app;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

abstract class Controller
{
    // Méthode commune pour écrire dans la réponse
    protected function writeResponse(Response $response, string $content): Response
    {
        $response->getBody()->write($content);
        return $response;
    }

    // Méthode abstraite que chaque contrôleur devra implémenter
    abstract public function __invoke(Request $request, Response $response, $args);
}
