<?php

namespace gift\api\app\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class GetDefaultAction extends AbstractAction {
    public function __invoke(Request $request, Response $response, array $args): Response {

        $data = ['message' => 'Welcome to the API!'];
        $jsonData = json_encode(['type' => 'message', 'data' => $data]);
        $response->getBody()->write($jsonData);
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }
}
