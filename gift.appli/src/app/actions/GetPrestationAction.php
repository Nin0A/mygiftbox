<?php

namespace gift\appli\app\actions;

use gift\appli\app\actions\AbstractAction;
use gift\appli\models\Prestation;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpNotFoundException;
use Slim\Views\Twig;


class GetPrestationAction extends AbstractAction{
    public function __invoke(Request $request, Response $response, array $args): Response{

        $view = Twig::fromRequest($request);
        $queryParams = $request->getQueryParams(); //récupère QueryParams
        if (isset($queryParams['id'])) { //si l'id est renseignée
            $id = $queryParams['id'];
            $prestation = Prestation::find($id);
            if (!$prestation) { //on affiche la prestation
                throw new HttpBadRequestException($request,"La prestation n'existe pas");
            }
        } else { //si prestation n'est pas trouvée -> Erreur 
        throw new HttpNotFoundException($request,"ID de la prestation non defini");
        } //si il n'y a pas d'id dans QueryParams alors Erreur

        return $view->render($response, 'prestation.twig', ['prestation' => $prestation]);
    }
}