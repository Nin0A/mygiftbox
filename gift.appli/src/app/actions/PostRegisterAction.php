<?php

namespace gift\appli\app\actions;

use gift\appli\core\services\auth\AuthService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use gift\appli\core\services\catalogue as Exception;
use Slim\Views\Twig;


class PostRegisterAction
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
                'password1' => $data['password1'] ?? null,
                'password2' => $data['password2'] ?? null,
                'csrf' => $data['csrf'] ?? null // Utiliser le token CSRF de la requête
            ];

            $authService->register($values);

            return $response->withHeader('Location', '/')->withStatus(302);

        }catch(\Exception $e){

            $_SESSION['error_message'] = $e->getMessage();

            return $response->withHeader('Location', '/account/register')->withStatus(302);
        }
    }
}
