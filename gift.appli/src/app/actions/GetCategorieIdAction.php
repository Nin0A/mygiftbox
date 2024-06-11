<?php

namespace gift\appli\app\actions;

use gift\appli\app\actions\AbstractAction;
use gift\appli\core\services\catalogue\CatalogueService;
use gift\appli\core\services\catalogue\CatalogueServiceNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;
use gift\appli\core\services\auth\AuthService;


class GetCategorieIdAction extends AbstractAction
{
    public function __invoke(Request $request, Response $response, array $args): Response
    {

        //Initialisation du Catalogue
        $catalogueService = new CatalogueService();

        // Initialisation de Twig
        $view = Twig::fromRequest($request);

        // Vérification si un ID est passé dans les arguments de la route
        try {

            $user=null;
            if(isset($_SESSION['USER']))
                $user=$_SESSION['USER'];
            
            if (isset($args['id'])) {
                $order = $request->getQueryParams()['order'] ?? 'asc';

                return $view->render($response, 'categorieView.html.twig', [
                    'userIsLoggedIn'=>AuthService::isAuthenticate(),
                'user'=>$user,
                    'categorie' => $catalogueService->getCategorieById($args['id']),
                    'prestations' => $catalogueService->sortPrestationByTarif($args['id'], $order),
                    'order' => $order,

                ]);

            } else {

                return $view->render($response, 'categories.html.twig',
                 ['userIsLoggedIn'=>AuthService::isAuthenticate(),
                'user'=>$user,
                'categories' => $catalogueService->getCategories()]);

            }
        } catch (CatalogueServiceNotFoundException $e) {
            throw new CatalogueServiceNotFoundException('Problème de bdd');
        }
    }
}
