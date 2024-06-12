<?php

namespace gift\appli\app\actions;

use gift\appli\core\services\auth\AuthService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

use Exception;

class PostLoginAction
{


    public function __invoke(Request $request, Response $response, array $args): Response
    {

        try {

            $view = Twig::fromRequest($request);
            // Récupérer les données du formulaire
            $data = $request->getParsedBody();

            // Créer une instance du service de catalogue
            $authService = new AuthService();

            // Préparer les valeurs pour la création de la catégorie
            $values = [
                'email' => $data['email'] ?? null,
                'password' => $data['password'] ?? null,
                'csrf' => $data['csrf'] ?? null // Utiliser le token CSRF de la requête
            ];


            // Créer une nouvelle catégorie
            $authService->login($values);


            // Rediriger vers la liste des catégories après la création
            return $response->withHeader('Location', '/')->withStatus(302);
        } catch (Exception $e) {
            return $view->render(
                $response,
                'error.html.twig',
                [
                    'message_error' => $e->getMessage(),
                    'code_error' => $e->getCode()
                ]
            );
        }
    }
}
