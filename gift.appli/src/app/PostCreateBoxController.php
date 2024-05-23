<?php

namespace gift\appli\app;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use gift\appli\models\Box;

class PostCreateBoxController extends Controller
{
    public function __invoke(Request $request, Response $response, $args)
    {
        $res = '';

        $id_box = $request->getParsedBody()['inputString']; // Récupérer les champs du formulaire

        $box = Box::select('libelle', 'description', 'montant')->where('id', '=', $id_box)->first();

        if ($box) { // Si la box existe, afficher les informations
            $res .= 'ID : ' . $id_box . '<br>';
            $res .= 'Libelle : ' . $box->libelle . '<br>';
            $res .= 'Description : ' . $box->description . '<br>';
            $res .= 'Montant : ' . $box->montant . '<br>';
        } else {
            // Si la box n'existe pas, afficher une erreur
            $res .= 'La box ' . $id_box . ' n\'existe pas!';
        }

        return $this->writeResponse($response, $res);
    }
}
