<?php

namespace gift\appli\app\actions;

use gift\appli\app\utils\CsrfService;
use gift\appli\core\services\auth\AuthService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use gift\appli\core\services\coffret\CoffretService;
use Slim\Views\Twig;

class GetBuyAction extends AbstractAction {
    public function __invoke(Request $request, Response $response, array $args): Response {
        $coffretService = new CoffretService();

        try{
        
            $user = null;
            if (isset($_SESSION['USER']))
                $user = $_SESSION['USER'];

            $error_message = $_SESSION['error_message'] ?? null;
            unset($_SESSION['error_message']);

            

            $view = Twig::fromRequest($request);


            if (isset($args['id'])) {

                $coffretService->getBoxById($args['id']);

                return $view->render(
                    $response,
                    'payement.html.twig',
                    [
                        'coffrets' => $coffretService->getBoxesByUser($user),
                        'user' => $user,
                        'userIsLoggedIn' => AuthService::isAuthenticate(),
                        'csrf' => CsrfService::generate(),
                        'currentCoffret' => $coffretService->getBoxById($args['id']),
                    ]
                );
            }


        }catch(\Exception $e){
            return $view->render($response, 'error.html.twig',
            ['message_error'=>$e->getMessage(),
            'code_error'=>$e->getCode()]);
        }


        return $view->render($response, 'payement.html.twig', [
            'userIsLoggedIn' => AuthService::isAuthenticate(),
            'coffrets' => $coffretService->getBoxesByUser($user),
            'csrf' => CsrfService::generate(),
            'user'=>$user,
        ]);
    }
}
