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



class GetBoxCreateAction extends AbstractAction{
    public function __invoke(Request $request, Response $response, array $args): Response{

        $catalogueService = new CatalogueService();

        $user=null;
        if(isset($_SESSION['USER']))
            $user=$_SESSION['USER'];

        $coffretService = new CoffretService();
        $view = Twig::fromRequest($request);
        return $view->render($response, 'get_box_create.html.twig',
        ['userIsLoggedIn'=>AuthService::isAuthenticate(),
        'prestations' => $catalogueService->getPrestations(),
        'user'=>$user,
        'coffrets' => $coffretService->getBoxesByUser($_SESSION['USER']),
        'csrf'=> CsrfService::generate()]);
    }
}