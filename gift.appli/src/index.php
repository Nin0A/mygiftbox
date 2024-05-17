<?php

    /**
     * require
     */
    require "vendor/autoload.php";

    /**
     * use
     */
    use gift\appli\models\Box;
    use gift\appli\models\Categorie;
    use gift\appli\models\Prestation;
    use gift\appli\models\User;

    use Illuminate\Database\Capsule\Manager as DB;

    /**
     * Initialisation de la base de donnée
     */
    $db = new DB();

    $db->addConnection(parse_ini_file("conf/gift.db.conf.ini.dist"));

    $db->setAsGlobal();
    $db->bootEloquent();

    //N°1 lister les prestations ; pour chaque prestation, afficher le libellé, la description, le tarif et l'unité.
    print('<h1>Requête n°1</h1>');

    $prestations_1 = Prestation::all();
    $i=1;

    foreach($prestations_1 as $p)
    {
        print('Préstation '.$i.' : '.$p->libelle.' / '.$p->description.' / '.$p->tarif.'<br>');
        $i++;
    }

    //N°2 idem, mais en affichant de plus la catégorie de la prestation. On utilisera un chargement lié (eager loading).
    print('<h1>Requête n°2</h1>');
    $i=0;
    $prestations_2 = Prestation::all();

    foreach($prestations_2 as $p)
    {
        print('Préstation '.$i.' : '.$p->libelle.' / '.$p->description.' / '.$p->tarif.' / Catégorie : '.$p->categorie->libelle.'<br>');
        $i++;
    }
    
    //N°3 afficher la catégorie 3 (libellé) et la liste des prestations (libellé, tarif, unité) de cette catégorie.
    print('<h1>Requête n°3</h1>');

    $categorie_3 = Categorie::find(3);
  

    $prestations_3 = $categorie_3->prestation();

    print('Préstation de la catégorie n°3 : <br>');
    foreach($prestations_2 as $p) print('     - Préstation : '.$p->libelle.' / '.$p->tarif.' / '.$p->unite.'<br>');
    
    //N°4 afficher la box d'ID 360bb4cc-e092-3f00-9eae-774053730cb2 : libellé, description, montant.
    print('<h1>Requête n°4</h1>');

    $box_4 = Box::select('libelle','description','montant')->where('id','=','360bb4cc-e092-3f00-9eae-774053730cb2')->first();

    print($box_4->libelle.' / '.$box_4->description.' / '.$box_4->montant);

    //N°5 idem, en affichant en plus les prestations prévues dans la box (libellé, tarif, unité, quantité).
    print('<h1>Requête n°5</h1>');

    $box_5 = Box::select('libelle','description','montant')->where('id','=','360bb4cc-e092-3f00-9eae-774053730cb2')->first();

    $prestations_5 = $box_5->prestation;

    print(' Box avec id 360bb4cc-e092-3f00-9eae-774053730cb2  : '.$box_5->libelle.' / '.$box_5->description.' / '.$box_5->montant.'<br>');

    foreach($prestations_5 as $p) print('     - Préstation : '.$p->libelle.' / '.$p->tarif.' / '.$p->unite.'<br>');
    
    //N°6 Créer une box et lui ajouter 3 prestations. L’identifiant de la box est un UUID. Consulter la documentation Eloquent pour la génération de cet identifiant.
    
    print('<h1>Requête n°6</h1>');
    print('<h2>Voir code.</h2>');

    //création de la nouvelle box
    // $new_box=new Box();
    // $new_box->token='test';
    // $new_box->libelle='test';
    // $new_box->save();

    //ajout des associationsn avec 3 prestations
    // $prestations_6_1 = Prestation::find('4cca8b8e-0244-499b-8247-d217a4bc542d');
    // $prestations_6_2 = Prestation::find('14872d96-97d6-4a9f-8a28-463886fea622');
    // $prestations_6_3 = Prestation::find('63cdce06-cd63-4fbe-9695-885d3cb64c7b');

    // $new_box->prestation()->attach($prestations_6_1->id, ['quantite' => 111] );
    // $new_box->prestation()->attach($prestations_6_2->id, ['quantite' => 222]);
    // $new_box->prestation()->attach($prestations_6_3->id, ['quantite' => 333]);

