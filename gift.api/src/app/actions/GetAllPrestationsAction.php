<?php

namespace gift\api\app\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;
use gift\api\core\services\catalogue\CatalogueService;
use gift\api\core\services\catalogue\CatalogueServiceNotFoundException;


class GetAllPrestationsAction extends AbstractAction {
    public function __invoke(Request $request, Response $response, array $args): Response {

        $catalogueService = new CatalogueService();

        try {
            $prestations = $catalogueService->getPrestations();
            $data = ['prestations' => $prestations];
            $jsonData = json_encode(['type' => 'resource', 'data' => $data]);
            $response->getBody()->write($jsonData);
            return $response
               ->withHeader('Content-Type', 'application/json')
               ->withStatus(200);
                

        } catch (CatalogueServiceNotFoundException $e) {
            throw new HttpNotFoundException($request, "Les prestations n'ont pas pu être récupérées");
        }
    }
}

