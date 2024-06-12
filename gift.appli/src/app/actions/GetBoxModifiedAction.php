<?php

namespace gift\appli\app\actions;

use gift\appli\app\actions\AbstractAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;
use gift\appli\core\services\coffret\CoffretService;
use gift\appli\core\services\catalogue\CatalogueService;
use gift\appli\app\utils\CsrfService;
use gift\appli\core\services\auth\AuthService;



class GetBoxModifiedAction extends AbstractAction
{
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $catalogueService = new CatalogueService();

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
                    'get_box_create.html.twig',
                    [
                        'prestations' => $catalogueService->getPrestationsWithCategorie(),
                        'coffrets' => $coffretService->getBoxesByUser($user),
                        'user' => $user,
                        'error_message' => $error_message,
                        'userIsLoggedIn' => AuthService::isAuthenticate(),
                        'csrf' => CsrfService::generate(),
                        'currentCoffret' => $coffretService->getBoxById($args['id']),
                        'currentPrestations' => $coffretService->getPrestationsByBoxId($args['id'])
                    ]
                );
            }


        }catch(\Exception $e){
            return $view->render($response, 'error.html.twig',
            ['message_error'=>$e->getMessage(),
            'code_error'=>$e->getCode()]);
        }


        return $view->render($response, 'get_box_create.html.twig',
        ['userIsLoggedIn'=>AuthService::isAuthenticate(),
        'prestations' => $catalogueService->getPrestationsWithCategorie(),
        'error_message'=> $error_message,
        'user'=>$user,
        'message_error',
        'coffrets' => $coffretService->getBoxesByUser($_SESSION['USER']),
        'csrf'=> CsrfService::generate()]);
    }
}