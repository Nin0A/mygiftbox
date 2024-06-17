<?php

// Déclarer le namespace pour cette classe
namespace gift\appli\app\actions;

// Importer les classes et interfaces nécessaires
use gift\appli\app\actions\AbstractAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

// Déclaration de la classe GetErrorAction qui hérite de AbstractAction
class GetErrorAction extends AbstractAction
{
    // Implémentation de la méthode __invoke qui sera appelée lorsqu'une instance de cette classe sera utilisée comme une fonction
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        // Initialisation de Twig à partir de la requête pour rendre les templates
        $view = Twig::fromRequest($request);

        // Rendre le template 'error.html.twig' avec les données nécessaires
        return $view->render($response, 'error.html.twig', [
            'code_error' => $args['code_error'], // Code d'erreur passé en argument
            'message_error' => $args['message_error'], // Message d'erreur passé en argument
        ]);
    }
}
