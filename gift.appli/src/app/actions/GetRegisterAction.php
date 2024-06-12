<?php

namespace gift\appli\app\actions;

use gift\appli\app\actions\AbstractAction;
use gift\appli\app\utils\CsrfService;
use gift\appli\core\services\auth\AuthService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;


class GetRegisterAction extends AbstractAction{
    public function __invoke(Request $request, Response $response, array $args): Response{
        try{
        //catalogue service
        $view = Twig::fromRequest($request);

      
        $error_message = $_SESSION['error_message'] ?? null;
        unset($_SESSION['error_message']);



        return $view->render($response, 'register.html.twig',
                            ['error_message'=>$error_message,
                            'userIsAuthenticate'=>AuthService::isAuthenticate(),
                            'csrf'=> CsrfService::generate()]);

        }catch(\Exception $e){
            return $view->render($response, 'error.html.twig',
            ['message_error'=>$e->getMessage(),
            'code_error'=>$e->getCode()]);
        }
    }
}