<?php

namespace gift\appli\app\actions;

use gift\appli\app\actions\AbstractAction;
use gift\appli\models\Categorie;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;
use Slim\Views\Twig;

class GetCategorieIdAction extends AbstractAction
{
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $categories = Categorie::all();
        $view = Twig::fromRequest($request);
        return $view->render($response, 'categories.twig', ['categorie' => $categories]);
    }
}
