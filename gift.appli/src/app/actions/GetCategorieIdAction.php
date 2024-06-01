<?php

namespace gift\appli\app\actions;

use gift\appli\app\actions\AbstractAction;
use gift\appli\core\services\catalogue\CatalogueService;
use gift\appli\core\services\catalogue\CatalogueServiceNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

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
            
            if (isset($args['id'])) {

                return $view->render($response, 'categorieView.html.twig', [
                    'categorie' => $catalogueService->getCategorieById($args['id']),
                    'prestations' => $catalogueService->getPrestationsbyCategorie($args['id'])
                ]);

            } else {

                return $view->render($response, 'categories.html.twig', ['categories' => $catalogueService->getCategories()]);

            }
        } catch (CatalogueServiceNotFoundException $e) {
            throw new CatalogueServiceNotFoundException('Problème de bdd');
        }
    }
}
