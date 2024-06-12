<?php

namespace gift\appli\app\actions;

use gift\appli\app\actions\AbstractAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;
use gift\appli\core\services\auth\AuthService;



class GetErrorAction extends AbstractAction{
    public function __invoke(Request $request, Response $response, array $args): Response{
        //Route par défaut
        $view = Twig::fromRequest($request);

        return $view->render($response, 'error.html.twig',[
            'code_error'=>$args['code_error'],
            'message_error'=>$args['code_error']
        
        ]);
    }
}