<?php

namespace gift\appli\app\actions;

use gift\appli\core\services\auth\AuthService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;
use Exception;

class PostRegisterAction
{
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        try {
            // Récupérer l'instance de Twig depuis la requête
            $view = Twig::fromRequest($request);

            // Récupérer les données du formulaire
            $data = $request->getParsedBody();

            // Créer une instance du service d'authentification
            $authService = new AuthService();

            // Préparer les valeurs pour l'inscription
            $values = [
                'email' => $data['email'] ?? null,
                'password1' => $data['password1'] ?? null,
                'password2' => $data['password2'] ?? null,
                'csrf' => $data['csrf'] ?? null // Utiliser le token CSRF de la requête
            ];

            // Appeler la méthode pour enregistrer l'utilisateur
            $authService->register($values);

            // Rediriger vers la page d'accueil après l'inscription réussie
            return $response->withHeader('Location', '/')->withStatus(302);

        } catch (Exception $e) {
            // En cas d'erreur, rediriger vers la page d'inscription avec un message d'erreur
            $_SESSION['error_message'] = $e->getMessage();
            return $response->withHeader('Location', '/account/register')->withStatus(302);
        }
    }
}
