<?php

// Déclarer le namespace pour cette classe
namespace gift\appli\app\actions;

// Importer les classes et interfaces nécessaires
use gift\appli\app\actions\AbstractAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;
use gift\appli\core\services\auth\AuthService;

// Déclaration de la classe GetDefaultAction qui hérite de AbstractAction
class GetDefaultAction extends AbstractAction
{
    // Implémentation de la méthode __invoke qui sera appelée lorsqu'une instance de cette classe sera utilisée comme une fonction
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        try {
            // Initialisation de Twig à partir de la requête pour rendre les templates
            $view = Twig::fromRequest($request);

            // Vérifier si l'utilisateur est connecté
            $user = null;
            if (AuthService::isAuthenticate()) {
                $user = $_SESSION['USER'];
            }

            // Rendre le template 'welcome_page.html.twig' avec les données nécessaires
            return $view->render($response, 'welcome_page.html.twig', [
                'user' => $user, // Informations sur l'utilisateur
                'userIsLoggedIn' => AuthService::isAuthenticate() // Vérifier si l'utilisateur est authentifié
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
