<?php

namespace gift\appli\app\actions;

use gift\appli\app\actions\AbstractAction;
use gift\appli\app\utils\CsrfService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;
use gift\appli\core\services\auth\AuthService;



class GetCategorieCreateAction extends AbstractAction{
    public function __invoke(Request $request, Response $response, array $args): Response{
        try{
        //catalogue service
        $view = Twig::fromRequest($request);

        $user=null;
        if(isset($_SESSION['USER']))
            $user=$_SESSION['USER'];

        return $view->render($response, 'get_categorie_create.html.twig',[
            'userIsLoggedIn'=>AuthService::isAuthenticate(),
            'user'=>$user,
            'csrf'=> CsrfService::generate()]);
        }catch(\Exception $e){
            return $view->render($response, 'error.html.twig',
            ['message_error'=>$e->getMessage(),
            'code_error'=>$e->getCode()]);
        }
    }
}