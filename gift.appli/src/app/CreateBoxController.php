<?php

namespace gift\appli\app;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class CreateBoxController extends Controller
{
    public function __invoke(Request $request, Response $response, $args)
    {
        // Création du formulaire
        $res = '<form action="./create" method="post">
                <label for="inputString">Entrez ID de box :</label>
                <input type="text" id="inputString" name="inputString" required>
                <button type="submit">Soumettre</button>
                </form>';

        return $this->writeResponse($response, $res);
    }
}
