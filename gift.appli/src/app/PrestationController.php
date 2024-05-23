<?php

namespace gift\appli\app;

use gift\appli\models\Prestation;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class PrestationController extends Controller
{
    public function __invoke(Request $request, Response $response, $args)
    {
        $res = '<form action="./prestation" method="get">
                <label for="inputId">Entrez ID de la prestation :</label>
                <input type="text" id="inputId" name="id" required>
                <button type="submit">Soumettre</button>
                </form>';

        $queryParams = $request->getQueryParams();
        if (isset($queryParams['id'])) {
            $id = $queryParams['id'];
            $prestation = Prestation::find($id);

            if ($prestation) {
                // on affiche la prestation
                $res .= ' - Libelle : ' . $prestation->libelle . '<br>';
                $res .= ' - Description : ' . $prestation->description . '<br>';
                $res .= ' - Tarif : ' . $prestation->tarif . ' €<br>';
            } else {
                // si prestation n'est pas trouvée -> Erreur
                $res .= '<h1>Prestation non trouvée</h1>';
            }
        }

        return $this->writeResponse($response, $res);
    }
}
