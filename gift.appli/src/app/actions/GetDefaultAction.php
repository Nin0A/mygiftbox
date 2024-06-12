<?php

namespace gift\appli\app\actions;

use gift\appli\app\actions\AbstractAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;
use gift\appli\core\services\auth\AuthService;



class GetDefaultAction extends AbstractAction{
    public function __invoke(Request $request, Response $response, array $args): Response{

        try{
        //Route par défaut
        $view = Twig::fromRequest($request);

        $user=null;

        if(AuthService::isAuthenticate())
            $user=$_SESSION['USER'];

        return $view->render($response, 'welcome_page.html.twig',[
            'user'=>$user,
            'userIsLoggedIn'=>AuthService::isAuthenticate()]);
        }catch(\Exception $e){
                return $view->render($response, 'error.html.twig',
                ['message_error'=>$e->getMessage(),
                'code_error'=>$e->getCode()]);
            }
    }
}