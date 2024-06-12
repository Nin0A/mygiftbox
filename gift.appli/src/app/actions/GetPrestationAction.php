<?php

namespace gift\appli\app\actions;

use gift\appli\app\actions\AbstractAction;
use gift\appli\core\services\catalogue\CatalogueService;
use gift\appli\core\services\catalogue\CatalogueServiceNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpNotFoundException;
use Slim\Views\Twig;
use gift\appli\core\services\auth\AuthService;



class GetPrestationAction extends AbstractAction
{
    public function __invoke(Request $request, Response $response, array $args): Response
    {

        $catalogue_service = new CatalogueService();

        $user=null;
        if(isset($_SESSION['USER']))
            $user=$_SESSION['USER'];
        
        $view = Twig::fromRequest($request);
        $queryParams = $request->getQueryParams(); //récupère QueryParams

        try {

            if (isset($queryParams['id']))
                return $view->render($response, 'prestation.html.twig',
            ['userIsLoggedIn'=>AuthService::isAuthenticate(),
            'user'=>$user,
            'prestation' => $catalogue_service->getPrestationById($queryParams['id'])]);
            
            else
                throw new HttpBadRequestException($request, "ID de la prestation non defini");

        } catch(\Exception $e){
            return $view->render($response, 'error.html.twig',
            ['message_error'=>$e->getMessage(),
            'code_error'=>$e->getCode()]);
        }
    }
}