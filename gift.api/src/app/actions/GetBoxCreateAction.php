<?php

namespace gift\api\app\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use gift\api\core\services\coffret\CoffretService;
use gift\api\core\services\catalogue\CatalogueService;
use gift\api\app\utils\CsrfService;
use gift\api\core\services\auth\AuthService;

class GetBoxCreateAction extends AbstractAction {
    public function __invoke(Request $request, Response $response, array $args): Response {

        $catalogueService = new CatalogueService();
        $coffretService = new CoffretService();
        $_SESSION['USER'] = null;

        $data = [
            'prestations' => $catalogueService->getPrestations(),
            'coffrets' => $coffretService->getBoxes(),
            'csrf' => CsrfService::generate()
        ];

        $jsonData = json_encode(['type' => 'resource', 'data' => $data]);
        $response->getBody()->write($jsonData);
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }
}
