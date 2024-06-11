<?php

namespace gift\api\app\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use gift\api\core\services\catalogue\CatalogueService;
use gift\api\core\services\catalogue\CatalogueServiceNotFoundException;

class GetCategorieIdAction extends AbstractAction {
    public function __invoke(Request $request, Response $response, array $args): Response {


        $catalogueService = new CatalogueService();

        try {
            if (isset($args['id'])) {
                $categorie = $catalogueService->getCategorieById($args['id']);
                $prestations = $catalogueService->sortPrestationByTarif($args['id'], $request->getQueryParams()['order'] ?? 'asc');
                $data = [
                    'categorie' => $categorie,
                    'prestations' => $prestations
                ];

                $jsonData = json_encode(['type' => 'resource', 'data' => $data]);
                $response->getBody()->write($jsonData);
                return $response
                    ->withHeader('Content-Type', 'application/json')
                    ->withStatus(200);
            } else {
                $categories = $catalogueService->getCategories();
                $data = ['categories' => $categories];
                $jsonData = json_encode(['type' => 'collection', 'data' => $data, 'count' => count($categories)]);
                $response->getBody()->write($jsonData);
                return $response
                    ->withHeader('Content-Type', 'application/json')
                    ->withStatus(200);
            }
        } catch (CatalogueServiceNotFoundException $e) {
            $errorData = [
                'error' => 'An error occurred while retrieving data.',
                'message' => $e->getMessage()
            ];

            $errorJsonData = json_encode(['type' => 'error', 'data' => $errorData]);
            $response->getBody()->write($errorJsonData);
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(500); 
        }
    }
}
