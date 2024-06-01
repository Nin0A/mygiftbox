<?php

namespace gift\appli\app\actions;

use gift\appli\app\actions\AbstractAction;
use gift\appli\core\domain\models\Prestation;
use gift\appli\core\services\catalogue\CatalogueService;
use gift\appli\core\services\catalogue\CatalogueServiceNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpNotFoundException;
use Slim\Views\Twig;


class GetPrestationAction extends AbstractAction
{
    public function __invoke(Request $request, Response $response, array $args): Response
    {

        $catalogue_service = new CatalogueService();

        $view = Twig::fromRequest($request);
        $queryParams = $request->getQueryParams(); //récupère QueryParams

        try {

            if (isset($queryParams['id']))
                return $view->render($response, 'prestation.html.twig', ['prestation' => $catalogue_service->getPrestationById($queryParams['id'])]);
            
            else
                throw new HttpBadRequestException($request, "ID de la prestation non defini");

        } catch (CatalogueServiceNotFoundException $e) {
            throw new HttpNotFoundException($request, "La prestation n'existe pas");
        }
    }
}