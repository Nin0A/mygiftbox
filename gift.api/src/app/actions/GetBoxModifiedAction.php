<?php

namespace gift\api\app\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use gift\api\core\services\coffret\CoffretService;
use gift\api\core\services\catalogue\CatalogueService;
use gift\api\app\utils\CsrfService;

class GetBoxModifiedAction extends AbstractAction {
    public function __invoke(Request $request, Response $response, array $args): Response {

        $catalogueService = new CatalogueService();
        $coffretService = new CoffretService();

        try {
            if (isset($args['id'])) {
                $currentCoffret = $coffretService->getBoxById($args['id']);
                $currentPrestations = $coffretService->getPrestationsByBoxId($args['id']);
                $data = [
                    'currentCoffret' => $currentCoffret,
                    'currentPrestations' => $currentPrestations
                ];
                $jsonData = json_encode(['type' => 'resource', 'data' => $data]);
                $response->getBody()->write($jsonData);
                return $response
                    ->withHeader('Content-Type', 'application/json')
                    ->withStatus(200);
            }

        } catch (\Exception $e) {
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

        $emptyData = ['message' => 'No data found.'];
        $emptyJsonData = json_encode(['type' => 'resource', 'data' => $emptyData]);

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(404)
            ->getBody()->write($emptyJsonData);
    }
}
