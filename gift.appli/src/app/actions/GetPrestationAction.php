<?php

// Déclarer le namespace pour cette classe
namespace gift\appli\app\actions;

// Importer les classes et interfaces nécessaires
use gift\appli\app\actions\AbstractAction;
use gift\appli\core\services\catalogue\CatalogueService;
use gift\appli\core\services\catalogue\CatalogueServiceNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpNotFoundException;
use Slim\Views\Twig;
use gift\appli\core\services\auth\AuthService;

// Déclaration de la classe GetPrestationAction qui hérite de AbstractAction
class GetPrestationAction extends AbstractAction
{
    // Implémentation de la méthode __invoke qui sera appelée lorsqu'une instance de cette classe sera utilisée comme une fonction
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        // Initialisation du service de catalogue
        $catalogue_service = new CatalogueService();

        // Initialisation de Twig à partir de la requête pour rendre les templates
        $view = Twig::fromRequest($request);

        // Récupération de l'utilisateur actuellement connecté
        $user = null;
        if (isset($_SESSION['USER'])) {
            $user = $_SESSION['USER'];
        }

        // Récupération des paramètres de la requête
        $queryParams = $request->getQueryParams();

        try {
            // Vérifier si l'ID de la prestation est défini dans les paramètres de la requête
            if (isset($queryParams['id'])) {
                // Rendre le template 'prestation.html.twig' avec les données nécessaires
                return $view->render($response, 'prestation.html.twig', [
                    'userIsLoggedIn' => AuthService::isAuthenticate(), // Vérifier si l'utilisateur est authentifié
                    'user' => $user, // Informations sur l'utilisateur
                    'prestation' => $catalogue_service->getPrestationById($queryParams['id']) // Détails de la prestation
                ]);
            } else {
                // Lancer une exception si l'ID de la prestation n'est pas défini
                throw new HttpBadRequestException($request, "ID de la prestation non défini");
            }

        } catch (HttpBadRequestException $e) {
            // Gérer l'exception HTTP Bad Request (400)
            return $view->render($response, 'error.html.twig', [
                'message_error' => $e->getMessage(),
                'code_error' => $e->getCode()
            ]);
        } catch (CatalogueServiceNotFoundException $e) {
            // Gérer l'exception si la prestation n'est pas trouvée
            return $view->render($response, 'error.html.twig', [
                'message_error' => $e->getMessage(),
                'code_error' => 404 // Code d'erreur 404
            ]);
        } catch (\Exception $e) {
            // Gérer toutes les autres exceptions
            return $view->render($response, 'error.html.twig', [
                'message_error' => $e->getMessage(),
                'code_error' => $e->getCode()
            ]);
        }
    }
}
