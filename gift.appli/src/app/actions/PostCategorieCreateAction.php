<?php

namespace gift\appli\app\actions;

use gift\appli\app\utils\CsrfService;
use gift\appli\core\services\catalogue\CatalogueService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use gift\appli\core\services\catalogue as Exception;

class PostCategorieCreateAction {
    public function __invoke(Request $request, Response $response, array $args): Response {
        // Récupérer les données du formulaire
        $data = $request->getParsedBody();

        // Créer une instance du service de catalogue
        $catalogueService = new CatalogueService();

        // Préparer les valeurs pour la création de la catégorie
        $values = [
            'libelle' => $data['libelle'] ?? null,
            'description' => $data['description'] ?? null,
            'csrf' => $data['csrf'] ?? null // Utiliser le token CSRF de la requête
        ];

        // Créer une nouvelle catégorie
        try {
            $catalogueService->createCategorie($values);
        } catch (Exception\CatalogueServiceInvalidDataException $e) {
            // Gérer l'erreur, par exemple en redirigeant avec un message d'erreur
            return $response->withHeader('Location', '/categorie/create'.urlencode($e->getMessage()))
                            ->withStatus(500);
        }
        catch(Exception\CatalogueServiceNoDataFoundException $e){
            return $response->withHeader('Location', '/categorie/create'.urlencode($e->getMessage()))
                            ->withStatus(500);
        }

        // Rediriger vers la liste des catégories après la création
        return $response->withHeader('Location', '/categories')->withStatus(302);
    }
}
