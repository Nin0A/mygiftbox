<?php

namespace gift\appli\app\actions;

use gift\appli\app\actions\AbstractAction;
use gift\appli\core\services\coffret\CoffretService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class PostBoxModifiedAction extends AbstractAction
{
    public function __invoke(Request $request, Response $response, array $args): Response
    {

        $data = $request->getParsedBody();

        if (isset($data['submit']) && $data['submit'] === 'Save') {
            // Créer une instance du service de catalogue
            $coffretService = new CoffretService();

            $kdo = isset($data['kdo']) && $data['kdo'] === 'on' ? 1 : 0;
            $montant=0;

            $randomBytes = random_bytes(8);
            $uniqId = uniqid('', true);
            $csrfToken = bin2hex($randomBytes) . bin2hex($uniqId);

            // Préparer les valeurs pour la création de la catégorie
            $values = [
                'libelle' => $data['libelle'] ?? null,
                'description' => $data['description'] ?? null,
                'montant' => $montant,
                'kdo' => $kdo,
                'message_kdo' => $data['message_kdo'] ?? '',
                //'createur_id' =>
                'csrf' => $data['csrf'] ?? null,
                'token' => $csrfToken,
                'id'=> $args['id']
            ];

            var_dump($args['id']);

            $prestations = $data['prestations'] ?? [];

            $values['prestations'] = $prestations;
            
            $coffretService->modifiedBox($values);
            
            return $response->withHeader('Location', '/box/modified/'.$args['id'])->withStatus(302);
        }else{

            return $response->withHeader('Location', '/box/modified/'.$args['id'])->withStatus(302);
        }
    }
}