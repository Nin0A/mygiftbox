<?php

namespace gift\appli\app\actions;

use gift\appli\app\actions\AbstractAction;
use gift\appli\core\domain\models\Box;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class PostBoxCreateAction extends AbstractAction {
    public function __invoke(Request $request, Response $response, array $args): Response {
        
        $id_box = $request->getParsedBody()['inputString'];

        $box = Box::select('libelle', 'description', 'montant')->where('id', '=', $id_box)->first();

        if ($box) {
            $view = Twig::fromRequest($request);
            return $view->render($response, 'post_box_create.html.twig', ['boxcreate' => $box]);
        } else {
            $response->getBody()->write('La box ' . $id_box . ' n\'existe pas!');
            return $response->withStatus(404);
        }
    }
}
