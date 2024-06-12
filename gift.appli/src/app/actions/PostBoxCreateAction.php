<?php

namespace gift\appli\app\actions;

use gift\appli\app\actions\AbstractAction;

use gift\appli\core\services\coffret\CoffretService;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class PostBoxCreateAction extends AbstractAction
{
    public function __invoke(Request $request, Response $response, array $args): Response
    {

        try{

        $data = $request->getParsedBody();

        if (isset($data['submit']) && $data['submit'] === 'Save') {
            // Créer une instance du service de catalogue
            $coffretService = new CoffretService();

            $kdo = isset($data['kdo']) && $data['kdo'] === 'on' ? 1 : 0;
            //$montant = $data['montant'] === null ? 0 : $data['montant'];
            $montant=0;

            $randomBytes = random_bytes(8); // 16 bytes = 32 caractères hexadécimaux
            $uniqId = uniqid('', true); // Génère un identifiant unique basé sur la date et l'heure
            $csrfToken = bin2hex($randomBytes) . bin2hex($uniqId);

            // Préparer les valeurs pour la création de la catégorie
            $values = [
                'libelle' => $data['libelle'] ?? null,
                'description' => $data['description'] ?? null,
                'montant' => $montant,
                'kdo' => $kdo,
                'message_kdo' => $data['message_kdo'] ?? '',
                'csrf' => $data['csrf'] ?? null,
                'token' => $csrfToken,
            ];

            $prestations = $data['prestations'] ?? [];

            $values['prestations'] = $prestations;
            
            $coffretService->createBox($values);
            
            return $response->withHeader('Location', '/box/create')->withStatus(302);
        }else{

            return $response->withHeader('Location', '/box/create')->withStatus(302);
        }
    }catch(\Exception $e){
        return $response->withHeader('Location', '/box/create')->withStatus(302);
    }
    }
}
