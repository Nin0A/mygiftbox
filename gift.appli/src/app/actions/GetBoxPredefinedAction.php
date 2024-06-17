<?php

// Déclarer le namespace pour cette classe
namespace gift\appli\app\actions;

// Importer les classes et interfaces nécessaires
use gift\appli\app\actions\AbstractAction;
use gift\appli\core\services\catalogue\CatalogueService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;
use gift\appli\core\services\auth\AuthService;

// Déclaration de la classe GetBoxPredefinedAction qui hérite de AbstractAction
class GetBoxPredefinedAction extends AbstractAction
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

            // Rendre le template 'predefinedBoxesView.html.twig' avec les données nécessaires
            return $view->render($response, 'predefinedBoxesView.html.twig', [
                'userIsLoggedIn' => AuthService::isAuthenticate(), // Vérifier si l'utilisateur est authentifié
                'user' => $user, // Informations sur l'utilisateur
                'predefined_boxes' => $catalogueService->getBoxesPredefined(), // Obtenir les coffrets prédéfinis
            ]);

        } catch (\Exception $e) {
            // En cas d'exception, rendre un template d'erreur avec le message et le code d'erreur
            return $view->render($response, 'error.html.twig', [
                'message_error' => $e->getMessage(),
                'code_error' => $e->getCode()
            ]);
        }
    }
}
