<?php

namespace gift\api\app\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use gift\api\app\utils\CsrfService;

class GetCategorieCreateAction extends AbstractAction {
    public function __invoke(Request $request, Response $response, array $args): Response {

        $data = ['csrf' => CsrfService::generate()];
        $jsonData = json_encode(['type' => 'resource', 'data' => $data]);
        $response->getBody()->write($jsonData);
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }
}
