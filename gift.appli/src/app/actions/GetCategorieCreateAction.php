<?php

// Déclarer le namespace pour cette classe
namespace gift\appli\app\actions;

// Importer les classes et interfaces nécessaires
use gift\appli\app\actions\AbstractAction;
use gift\appli\app\utils\CsrfService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;
use gift\appli\core\services\auth\AuthService;

// Déclaration de la classe GetCategorieCreateAction qui hérite de AbstractAction
class GetCategorieCreateAction extends AbstractAction
{
    // Implémentation de la méthode __invoke qui sera appelée lorsqu'une instance de cette classe sera utilisée comme une fonction
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        try {
            // Initialisation de Twig à partir de la requête pour rendre les templates
            $view = Twig::fromRequest($request);

            // Vérifier si un utilisateur est connecté en regardant dans la session
            $user = null;
            if (isset($_SESSION['USER'])) {
                $user = $_SESSION['USER'];
            }

            // Rendre le template 'get_categorie_create.html.twig' avec les données nécessaires
            return $view->render($response, 'get_categorie_create.html.twig', [
                'userIsLoggedIn' => AuthService::isAuthenticate(), // Vérifier si l'utilisateur est authentifié
                'user' => $user, // Informations sur l'utilisateur
                'csrf' => CsrfService::generate() // Générer un token CSRF pour la sécurité
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
