<?php

namespace gift\api\app\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpNotFoundException;
use gift\api\core\services\catalogue\CatalogueService;
use gift\api\core\services\catalogue\CatalogueServiceNotFoundException;

class GetPrestationAction extends AbstractAction {
    public function __invoke(Request $request, Response $response, array $args): Response {

        $catalogueService = new CatalogueService();
        $queryParams = $request->getQueryParams();

        try {
            if (isset($queryParams['id'])) {
                $prestation = $catalogueService->getPrestationById($queryParams['id']);
                $data = ['prestation' => $prestation];
                $jsonData = json_encode(['type' => 'resource', 'data' => $data]);
                $response->getBody()->write($jsonData);
                return $response
                    ->withHeader('Content-Type', 'application/json')
                    ->withStatus(200);
            } else {
                throw new HttpBadRequestException($request, "ID de la prestation non défini");
            }
        } catch (CatalogueServiceNotFoundException $e) {
            throw new HttpNotFoundException($request, "La prestation n'existe pas");
        }
    }
}
