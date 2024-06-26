<?php

// Déclarer le namespace pour cette classe
namespace gift\appli\app\actions;

// Importer les classes et interfaces nécessaires
use gift\appli\app\actions\AbstractAction;
use gift\appli\app\utils\CsrfService;
use gift\appli\core\services\auth\AuthService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

// Déclaration de la classe GetLogoutAction qui hérite de AbstractAction
class GetLogoutAction extends AbstractAction
{
    // Implémentation de la méthode __invoke qui sera appelée lorsqu'une instance de cette classe sera utilisée comme une fonction
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        try {
            // Initialisation de Twig à partir de la requête pour rendre les templates
            $view = Twig::fromRequest($request);

            // Service d'authentification
            $authService = new AuthService();

            // Récupération de l'utilisateur actuellement connecté
            $user = null;
            if (AuthService::isAuthenticate()) {
                $user = $_SESSION['USER'];
            }

            // Appel de la méthode de déconnexion dans le service d'authentification
            $authService->logout();

            // Rendre le template 'welcome_page.html.twig' avec les données nécessaires
            return $view->render($response, 'welcome_page.html.twig', [
                'user' => $user, // Informations sur l'utilisateur
                'userIsLoggedIn' => AuthService::isAuthenticate(), // Vérifier si l'utilisateur est authentifié
                'csrf' => CsrfService::generate() // Générer un jeton CSRF
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
