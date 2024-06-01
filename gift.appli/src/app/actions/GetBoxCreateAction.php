<?php

namespace gift\appli\app\actions;

use gift\appli\app\actions\AbstractAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;


class GetBoxCreateAction extends AbstractAction{
    public function __invoke(Request $request, Response $response, array $args): Response{
        $view = Twig::fromRequest($request);
        return $view->render($response, 'get_box_create.html.twig');
    }
}