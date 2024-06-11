<?php

namespace gift\appli\app\actions;

use gift\appli\app\actions\AbstractAction;
use gift\appli\app\utils\CsrfService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;


class GetRegisterAction extends AbstractAction{
    public function __invoke(Request $request, Response $response, array $args): Response{

        


        //catalogue service
        $view = Twig::fromRequest($request);
        return $view->render($response, 'login.html.twig',
                            ['csrf'=> CsrfService::generate()]);
    }
}