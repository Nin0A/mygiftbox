<?php
declare(strict_types=1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

//BD
use gift\appli\models\Categorie;
use gift\appli\models\Prestation;
use gift\appli\models\Box;
use Illuminate\Database\Capsule\Manager as DB;

/**
 * Initialisation de la base de donnée
 */
$db = new DB();

$db->addConnection(parse_ini_file("gift.db.conf.ini.dist"));

$db->setAsGlobal();
$db->bootEloquent();


return function (\Slim\App $app): \Slim\App {

    //Route par défaut
    $app->get('/', function (Request $request, Response $response, $args) {
        $response->getBody()->write("Hello!");
        return $response;
    });

    
    /**
     * GET /categorie/{id}
     */
    $app->get('/categories[/{id}]', function (Request $request, Response $response, $args) {
       
        if (isset($args['id'])) { // si id est renseignée
            
            $id = $args['id'];
            $categorie = Categorie::find($id);

            if ($categorie) { //si catégorie trouvée avec l'id
                $res = '<h1>Catégorie ID ' . $id . '</h1>';
                $res .= ' - Libelle : ' . $categorie->libelle . '<br>';
                $res .= ' - Description : ' . $categorie->description . '<br>';
            } else $res = '<h1>Catégorie non trouvée</h1>'; //sinon catégorie n'existe pas

        } else { //si id PAS renseignée on affiche toutes les catégories
            $res = '<h1>Liste des catégories</h1>';
            $i = 0;
            $categories = Categorie::all();

            foreach ($categories as $categorie) {
                $res .= ' - Catégorie n°' . $i . ' : <a href ="./categories/'.($i+1).'">'. $categorie->libelle . '</a><br>';
                $i++;
            }
        }

        $response->getBody()->write($res);
        return $response;
    });

    /**
     * GET /prestation
     */
    $app->get('/prestation', function (Request $request, Response $response, $args) {

        $res='';
        $queryParams = $request->getQueryParams(); //récupère QueryParams
        if (isset($queryParams['id'])) { //si l'id est renseignée
            $id = $queryParams['id'];
            $prestation = Prestation::find($id);

            if ($prestation) { //on affiche la prestation
                $res .= ' - Libelle : ' . $prestation->libelle . '<br>';
                $res .= ' - Description : ' . $prestation->description . '<br>';
                $res .= ' - Tarif : ' . $prestation->tarif . ' €<br>';
            } else { //si prestation n'est pas trouvée -> Erreur
                $res = '<h1>Prestation non trouvée</h1>';
            }
        } else $res = '<h1>Erreur : ID de la prestation non fourni dans la query string</h1>'; //si il n'y a pas d'id dans QueryParams alors Erreur

        $response->getBody()->write($res);
        return $response;
    });

    /**
     * GET /box/create pour la méthode get on affiche le formulaire
     */
    $app->get('/box/create', function (Request $request, Response $response, $args) {

        //création du formulaire
        $res='  <form action="./create" method="post">
                <label for="inputString">Entrez ID de box :</label>
                <input type="text" id="inputString" name="inputString" required>
                <button type="submit">Soumettre</button>
                </form>';

        $response->getBody()->write($res);
        return $response;
    });

    /**
     * POST /box/create pour la méthode post on affiche la catégorie correspondante au formulaire rempli
     */
    $app->post('/box/create', function (Request $request, Response $response, $args) {

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
    });

    return $app;
};