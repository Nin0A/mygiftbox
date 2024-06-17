<?php

namespace gift\appli\app\actions;

use gift\appli\app\actions\AbstractAction;
use gift\appli\core\services\coffret\CoffretService;
use gift\appli\core\services\coffret\CoffretServiceNotEnoughDataException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class PostBoxCreateAction extends AbstractAction
{
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        try {
            // Récupérer les données du corps de la requête
            $data = $request->getParsedBody();

            // Vérifier si le formulaire a été soumis et le bouton "Save" a été cliqué
            if (isset($data['submit']) && $data['submit'] === 'Save') {
                // Créer une instance du service de coffret
                $coffretService = new CoffretService();

                // Déterminer si la case à cocher "kdo" est cochée
                $kdo = isset($data['kdo']) && $data['kdo'] === 'on' ? 1 : 0;

                // Montant initialisé à zéro par défaut
                $montant = 0;

                // Générer un token CSRF sécurisé
                $randomBytes = random_bytes(8); // 16 bytes = 32 caractères hexadécimaux
                $uniqId = uniqid('', true); // Génère un identifiant unique basé sur la date et l'heure
                $csrfToken = bin2hex($randomBytes) . bin2hex($uniqId);

                // Préparer les valeurs pour la création du coffret
                $values = [
                    'libelle' => $data['libelle'] ?? null,
                    'description' => $data['description'] ?? null,
                    'montant' => $montant,
                    'kdo' => $kdo,
                    'message_kdo' => $data['message_kdo'] ?? '',
                    'csrf' => $data['csrf'] ?? null, // Jeton CSRF reçu du formulaire
                    'token' => $csrfToken, // Jeton CSRF généré
                ];

                // Récupérer les prestations sélectionnées
                $prestations = $data['prestations'] ?? [];
                $values['prestations'] = $prestations;

                // Appeler la méthode pour créer un coffret avec les valeurs préparées
                $coffretService->createBox($values);

                // Rediriger vers la page de création de coffret
                return $response->withHeader('Location', '/box/create')->withStatus(302);
            } else {
                // Rediriger vers la page de création de coffret si le formulaire n'a pas été soumis
                return $response->withHeader('Location', '/box/create')->withStatus(302);
            }
        } catch (CoffretServiceNotEnoughDataException $e) {
            // En cas de manque de données pour créer un coffret, enregistrer le message d'erreur dans la session
            $_SESSION['error_message'] = $e->getMessage();
            // Rediriger vers la page de création de coffret avec le statut 302 (Found)
            return $response->withHeader('Location', '/box/create')->withStatus(302);
        } catch (\Exception $e) {
            // En cas d'autre exception, rediriger vers la page de création de coffret avec le statut 302 (Found)
            return $response->withHeader('Location', '/box/create')->withStatus(302);
        }
    }
}
