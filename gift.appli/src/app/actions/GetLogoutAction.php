<?php

namespace gift\appli\app\actions;

use gift\appli\app\actions\AbstractAction;
use gift\appli\app\utils\CsrfService;
use gift\appli\core\services\auth\AuthService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;


class GetLogoutAction extends AbstractAction{
    public function __invoke(Request $request, Response $response, array $args): Response{

        try{
        //catalogue service
        $view = Twig::fromRequest($request);

        $authService = new AuthService();

        $user=null;

        if(AuthService::isAuthenticate()) $user=$_SESSION['USER'];

        $authService->logout();

        return $view->render($response, 'welcome_page.html.twig',[ 'user'=>$user,'userIsLoggedIn'=>AuthService::isAuthenticate(),'csrf'=> CsrfService::generate()]);
    
        }catch(\Exception $e){
            return $view->render($response, 'error.html.twig',
            ['message_error'=>$e->getMessage(),
            'code_error'=>$e->getCode()]);
        }
    }
}