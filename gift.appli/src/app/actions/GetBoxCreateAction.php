<?php

namespace gift\appli\app\actions;

use BadFunctionCallException;
use Exception;
use gift\appli\app\actions\AbstractAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;
use gift\appli\core\services\coffret\CoffretService;
use gift\appli\core\services\catalogue\CatalogueService;
use gift\appli\app\utils\CsrfService;
use gift\appli\core\services\auth\AuthService;



class GetBoxCreateAction extends AbstractAction{
    public function __invoke(Request $request, Response $response, array $args): Response{

    try{

        $view = Twig::fromRequest($request);
        $catalogueService = new CatalogueService();

        $user=null;
        if(isset($_SESSION['USER']))
            $user=$_SESSION['USER'];
        else
            throw new BadFunctionCallException('Utilisateur n\'est pas connecté',111);

        $coffretService = new CoffretService();
      
        return $view->render($response, 'get_box_create.html.twig',
        ['userIsLoggedIn'=>AuthService::isAuthenticate(),
        'prestations' => $catalogueService->getPrestations(),
        'user'=>$user,
        'coffrets' => $coffretService->getBoxesByUser($_SESSION['USER']),
        'csrf'=> CsrfService::generate()]);

    }catch(Exception $e){
        return $view->render($response, 'error.html.twig',
        ['message_error'=>$e->getMessage(),
        'code_error'=>$e->getCode()]);
    }
       
    }
}