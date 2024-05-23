<?php

namespace gift\appli\app\actions;

use gift\appli\app\actions\AbstractAction;
use gift\appli\models\Box;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


class PostBoxCreateAction extends AbstractAction{
    public function __invoke(Request $request, Response $response, array $args): Response{
        $res='';
        
        $id_box=$request->getParsedBody()['inputString']; //on récupère le/les champs du formulaire souhaités

        $box = Box::select('libelle','description','montant')->where('id','=',$id_box)->first();

        if($box){ //si la box existe on affiche les informations
            $res .= 'ID : '.$id_box.'<br>';
            $res .= 'Libelle : '.$box->libelle.'<br>';
            $res .= 'Description : '.$box->description.'<br>';
            $res .= 'Montant : '.$box->montant.'<br>';
            $response->getBody()->write($res);
        }else $response->getBody()->write('La box '.$id_box.' n\'existe pas!');  //si la box n'existe pas -> Erreur
     
        return $response;
       
    }
}