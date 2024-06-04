<?php

namespace gift\appli\app\actions;

use gift\appli\app\actions\AbstractAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;


class GetDefaultAction extends AbstractAction{
    public function __invoke(Request $request, Response $response, array $args): Response{
        //Route par défaut
        $view = Twig::fromRequest($request);

        return $view->render($response, 'welcome_page.html.twig');
    }
}