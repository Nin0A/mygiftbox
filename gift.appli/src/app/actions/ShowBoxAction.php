<?php

namespace gift\appli\app\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use gift\appli\core\services\coffret\CoffretService;
use Slim\Views\Twig;

class ShowBoxAction extends AbstractAction {
    public function __invoke(Request $request, Response $response, array $args): Response {
        $view = Twig::fromRequest($request);
        $token = $args['token'];

        $coffretService = new CoffretService();
        $coffret = $coffretService->getBoxByToken($token);

        if (!$coffret) {
            return $view->render($response, 'error.html.twig', [
                'message_error' => 'Coffret non trouvé.',
                'code_error' => 404
            ]);
        }

        return $view->render($response, 'show_box.html.twig', [
            'coffret' => $coffret,
        ]);
    }
}
