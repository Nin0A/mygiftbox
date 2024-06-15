<?php

namespace gift\appli\app\actions;

use gift\appli\app\utils\CsrfService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use gift\appli\core\services\auth\AuthService;
use gift\appli\core\services\coffret\CoffretService;
use Slim\Views\Twig;

class PostBuyAction extends AbstractAction {
    public function __invoke(Request $request, Response $response, array $args): Response {
        $view = Twig::fromRequest($request);
        $user=null;
        if (!AuthService::isAuthenticate()) {
            return $view->render($response, 'error.html.twig', [
                'message_error' => 'Vous devez être connecté pour effectuer un paiement.',
                'code_error' => 403
            ]);
        }

        $data = $request->getParsedBody();
        
        try {
            $paymentSuccess = true; 

            $coffretService = new CoffretService();
            $coffretService->updateBoxStatus($args['id'], 100);

            if ($paymentSuccess) {
               
                if(isset($_SESSION['USER']))
                    $user=$_SESSION['USER'];
                return $view->render($response, 'payement_success.html.twig', [
                    'message' => 'Paiement effectué avec succès!',
                    'userIsLoggedIn' => AuthService::isAuthenticate(),
                    'csrf' => CsrfService::generate(),
                    'user'=>$user,
                ]);
            } else {
                return $view->render($response, 'payement.html.twig', [
                    'error_message' => 'Le paiement a échoué. Veuillez réessayer.',
                    'csrf' => CsrfService::generate()
                ]);
            }
        } catch (\Exception $e) {
            return $view->render($response, 'error.html.twig', [
                'message_error' => $e->getMessage(),
                'code_error' => $e->getCode()
            ]);
        }
    }
}
