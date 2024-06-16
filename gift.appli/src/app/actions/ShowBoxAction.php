<?php

namespace gift\appli\app\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use gift\appli\core\services\coffret\CoffretService;
use gift\appli\core\services\auth\AuthService;
use gift\appli\app\utils\CsrfService;
use Slim\Views\Twig;

class ShowBoxAction extends AbstractAction {
    public function __invoke(Request $request, Response $response, array $args): Response {
        $view = Twig::fromRequest($request);
        $user = null;
        if (!AuthService::isAuthenticate()) {
            return $view->render($response, 'error.html.twig', [
                'message_error' => 'Vous devez être connecté pour effectuer un paiement.',
                'code_error' => 403
            ]);
        }
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
            'userIsLoggedIn' => AuthService::isAuthenticate(),
            'csrf' => CsrfService::generate(),
            'user' => $user,
        ]);
    }
}
