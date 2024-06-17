<?php

// Déclarer le namespace pour cette classe
namespace gift\appli\app\actions;

// Importer les classes et interfaces nécessaires
use BadFunctionCallException;
use Exception;
use gift\appli\app\actions\AbstractAction;
use gift\appli\core\services\coffret\CoffretServiceNotEnoughDataException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;
use gift\appli\core\services\coffret\CoffretService;
use gift\appli\core\services\catalogue\CatalogueService;
use gift\appli\app\utils\CsrfService;
use gift\appli\core\services\auth\AuthService;

// Déclaration de la classe GetBoxCreateAction qui hérite de AbstractAction
class GetBoxCreateAction extends AbstractAction {
    // Implémentation de la méthode __invoke qui sera appelée lorsqu'une instance de cette classe sera utilisée comme une fonction
    public function __invoke(Request $request, Response $response, array $args): Response {
        try {
            // Récupérer l'instance de Twig à partir de la requête pour rendre les templates
            $view = Twig::fromRequest($request);
            // Créer une instance de CatalogueService pour interagir avec le catalogue
            $catalogueService = new CatalogueService();

            $user = null;
            // Vérifier si l'utilisateur est connecté en regardant dans la session
            if (isset($_SESSION['USER'])) {
                $user = $_SESSION['USER'];
            } else {
                // Si l'utilisateur n'est pas connecté, lancer une exception
                throw new BadFunctionCallException('User is not connected', 401);
            }

            // Récupérer un éventuel message d'erreur de la session
            $error_message = $_SESSION['error_message'] ?? null;
            // Supprimer le message d'erreur de la session après l'avoir récupéré
            unset($_SESSION['error_message']);

            // Créer une instance de CoffretService pour interagir avec les coffrets
            $coffretService = new CoffretService();

            // Rendre le template 'get_box_create.html.twig' avec les données nécessaires
            return $view->render($response, 'get_box_create.html.twig', [
                'userIsLoggedIn' => AuthService::isAuthenticate(), // Vérifier si l'utilisateur est authentifié
                'prestations' => $catalogueService->getPrestationsWithCategorie(), // Obtenir les prestations avec leurs catégories
                'error_message' => $error_message, // Message d'erreur éventuel
                'user' => $user, // Informations sur l'utilisateur
                'coffrets' => $coffretService->getBoxesByUser($_SESSION['USER']), // Obtenir les coffrets de l'utilisateur
                'csrf' => CsrfService::generate() // Générer un token CSRF pour la sécurité
            ]);

        } catch (Exception $e) {
            // En cas d'exception, rendre le template 'error.html.twig' avec le message et le code d'erreur
            return $view->render($response, 'error.html.twig', [
                'message_error' => $e->getMessage(),
                'code_error' => $e->getCode()
            ]);
        }
    }
}
