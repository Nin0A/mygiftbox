<?php

// Déclarer le namespace pour cette classe
namespace gift\appli\app\actions;

// Importer les classes et interfaces nécessaires
use gift\appli\app\actions\AbstractAction;
use gift\appli\core\services\catalogue\CatalogueService;
use gift\appli\core\services\catalogue\CatalogueServiceNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;
use gift\appli\core\services\auth\AuthService;

// Déclaration de la classe GetCategorieIdAction qui hérite de AbstractAction
class GetCategorieIdAction extends AbstractAction
{
    // Implémentation de la méthode __invoke qui sera appelée lorsqu'une instance de cette classe sera utilisée comme une fonction
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        // Initialisation du CatalogueService pour interagir avec le catalogue
        $catalogueService = new CatalogueService();

        // Initialisation de Twig à partir de la requête pour rendre les templates
        $view = Twig::fromRequest($request);

        try {
            // Vérifier si un utilisateur est connecté en regardant dans la session
            $user = null;
            if (isset($_SESSION['USER'])) {
                $user = $_SESSION['USER'];
            }

            // Vérifier si un ID de catégorie est passé dans les arguments de la route
            if (isset($args['id'])) {
                // Récupérer le paramètre de tri des prestations (par tarif) depuis la requête, par défaut 'asc'
                $order = $request->getQueryParams()['order'] ?? 'asc';

                // Rendre le template 'categorieView.html.twig' avec les données de la catégorie et des prestations
                return $view->render($response, 'categorieView.html.twig', [
                    'userIsLoggedIn' => AuthService::isAuthenticate(), // Vérifier si l'utilisateur est authentifié
                    'user' => $user, // Informations sur l'utilisateur
                    'categorie' => $catalogueService->getCategorieById($args['id']), // Récupérer la catégorie par ID
                    'prestations' => $catalogueService->sortPrestationByTarif($args['id'], $order), // Trier les prestations par tarif
                    'order' => $order, // Ordre de tri
                ]);
            } else {
                // Si aucun ID n'est passé, rendre le template 'categories.html.twig' avec la liste des catégories
                return $view->render($response, 'categories.html.twig', [
                    'userIsLoggedIn' => AuthService::isAuthenticate(), // Vérifier si l'utilisateur est authentifié
                    'user' => $user, // Informations sur l'utilisateur
                    'categories' => $catalogueService->getCategories() // Récupérer toutes les catégories
                ]);
            }
        } catch (CatalogueServiceNotFoundException $e) {
            // En cas d'exception spécifique CatalogueServiceNotFoundException, rendre un template d'erreur avec le message et le code d'erreur 404
            return $view->render($response, 'error.html.twig', [
                'message_error' => $e->getMessage(),
                'code_error' => 404
            ]);
        } catch (\Exception $e) {
            // En cas d'exception générale, rendre un template d'erreur avec le message et le code d'erreur 500
            return $view->render($response, 'error.html.twig', [
                'message_error' => $e->getMessage(),
                'code_error' => 500
            ]);
        }
    }
}
