<?php

namespace gift\appli\app\actions;

use gift\appli\app\actions\AbstractAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;
use gift\appli\core\services\coffret\CoffretService;
use gift\appli\core\services\catalogue\CatalogueService;
use gift\appli\app\utils\CsrfService;


class GetBoxCreateAction extends AbstractAction{
    public function __invoke(Request $request, Response $response, array $args): Response{

        $catalogueService = new CatalogueService();

        $coffretService = new CoffretService();

        $view = Twig::fromRequest($request);
        return $view->render($response, 'get_box_create.html.twig',['prestations' => $catalogueService->getPrestations(), 'coffrets' => $coffretService->getBoxes(),'csrf'=> CsrfService::generate()]);
    }
}