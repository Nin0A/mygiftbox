<?php

namespace gift\appli\app\actions;

use gift\appli\app\actions\AbstractAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


class GetBoxCreateAction extends AbstractAction{
    public function __invoke(Request $request, Response $response, array $args): Response{

         //création du formulaire
         $res='  <form action="./create" method="post">
         <label for="inputString">Entrez ID de box :</label>
         <input type="text" id="inputString" name="inputString" required>
         <button type="submit">Soumettre</button>
         </form>';

        $response->getBody()->write($res);
        return $response;
    }
}