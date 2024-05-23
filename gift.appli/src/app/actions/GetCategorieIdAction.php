<?php

namespace gift\appli\app\actions;

use gift\appli\app\actions\AbstractAction;
use gift\appli\models\Categorie;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;

class GetCategorieIdAction extends AbstractAction
{
    public function __invoke(Request $request, Response $response, array $args): Response
    {

        if (isset($args['id'])) { // si id est renseignée

            $id = $args['id'];
            if (isset($id)) {
                $categorie = Categorie::find($id);

                if ($categorie) { //si catégorie trouvée avec l'id
                    $res = '<h1>Catégorie ID ' . $id . '</h1>';
                    $res .= ' - Libelle : ' . $categorie->libelle . '<br>';
                    $res .= ' - Description : ' . $categorie->description . '<br>';
                } else $res = '<h1>Catégorie non trouvée</h1>'; //sinon catégorie n'existe pas
            }else throw new HttpBadRequestException($request,"ID de la categorie non defini");
        } else { //si id PAS renseignée on affiche toutes les catégories
            $res = '<h1>Liste des catégories</h1>';
            $i = 0;
            $categories = Categorie::all();

            foreach ($categories as $categorie) {
                $res .= ' - Catégorie n°' . $i . ' : <a href ="./categories/' . ($i + 1) . '">' . $categorie->libelle . '</a><br>';
                $i++;
            }
        }

        $response->getBody()->write($res);
        return $response;
    }
}