<?php

namespace gift\appli\app\actions;

use BadFunctionCallException;
use Exception;
use gift\appli\app\actions\AbstractAction;
use gift\appli\core\services\coffret\CoffretServiceNotEnoughDataException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;
use gift\appli\core\services\coffret\CoffretService;
use gift\appli\core\services\catalogue\CatalogueService;
use gift\appli\app\utils\CsrfService;
use gift\appli\core\services\auth\AuthService;



class GetBoxCreateAction extends AbstractAction{
    public function __invoke(Request $request, Response $response, array $args): Response{

   

        $view = Twig::fromRequest($request);
        $catalogueService = new CatalogueService();

        $user=null;
        if(isset($_SESSION['USER']))
            $user=$_SESSION['USER'];
        else
            throw new BadFunctionCallException('User is not connected',111);

        $error_message = $_SESSION['error_message'] ?? null;
        unset($_SESSION['error_message']);

        $coffretService = new CoffretService();
      
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