<?php


namespace gift\api\core\services\coffret;
use gift\api\core\domain\entities\Prestation;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use \gift\api\app\utils\CsrfService;
use \gift\api\core\domain\entities\Box;
use Illuminate\Database\QueryException;

use \gift\api\core\services\coffret\CoffretServiceNotFoundException;




class CoffretService implements CoffretInterface
{
    /**
     * 
     */
    public function getBoxes(): array
    {
        try {

            $boxes = Box::all()->sortByDesc('updated_at');

            if (!$boxes) throw new ModelNotFoundException();

            return $boxes->toArray();

        } catch (ModelNotFoundException $e) {
            throw new CoffretServiceNotFoundException("Erreur interne", 500);
        }

       
    }


    /**
     * values libelle , description montant,  bool kdo, message kdo
     */
    public function createBox(array $values): void
    {
        // Vérification du token CSRF
        CsrfService::check($values['csrf']);

        $montant = 0;
        $categories = [];

        // Vérification que le nombre de prestations sélectionnées est d'au moins 2
        if (count($values['prestations']) < 2) {
            throw new CoffretServiceNotFoundException("Il faut sélectionner au moins 2 prestations.", 400);
        }

        // Création de la nouvelle boîte
        $newBox = new Box();
        $newBox->libelle = filter_var($values['libelle'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $newBox->description = filter_var($values['description'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $newBox->kdo = filter_var($values['kdo'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $newBox->message_kdo = filter_var($values['message_kdo'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $newBox->token = filter_var($values['token'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $newBox->created_at = date('Y-m-d H:i:s');
        $newBox->updated_at = date('Y-m-d H:i:s');

        // Sauvegarde initiale de la boîte
        $newBox->save();

        // Attacher les prestations et calculer le montant total
        foreach ($values['prestations'] as $prestationId) {
            $prestation = Prestation::find($prestationId);
            if ($prestation) {
                $categories[] = $prestation->cat_id;
                $newBox->prestation()->attach($prestationId, ['quantite' => 1]);
                $montant += $prestation->tarif;
            }
        }

        // Vérification que les prestations proviennent de catégories différentes
        if (count(array_unique($categories)) < 2) {
            $newBox->prestation()->detach();
            $newBox->delete();
            throw new CoffretServiceNotFoundException("Il faut sélectionner des prestations de catégories différentes.", 400);
        }

        // Mettre à jour le montant total et sauvegarder les modifications
        $newBox->montant = $montant;
        $newBox->save();
    }



    public function getBoxById(string $id): array
    {
        try {

            $boxes = Box::find($id);

            if (!$boxes) throw new ModelNotFoundException();

            return $boxes->toArray();

        } catch (ModelNotFoundException $e) {
            throw new CoffretServiceNotFoundException("Erreur interne", 500);
        }
    }


    public function getPrestationsByBoxId(string $id)
    {
        try {

            $res=[];

            $box_5 = Box::find($id);
            $prestations_5 = $box_5->prestation()->get();


            foreach($prestations_5 as $p) array_push($res,$p->id);


            return $res;

        } catch (ModelNotFoundException $e) {
            throw new CoffretServiceNotFoundException("Erreur interne", 500);
        }
    }

    public function modifiedBox(array $values): void
    {
        try {
            // Vérification du token CSRF
            CsrfService::check($values['csrf']);

            $montant = 0;
            $categories = [];

            // Vérification que le nombre de prestations sélectionnées est d'au moins 2
            if (count($values['prestations']) < 2) {
                throw new CoffretServiceNotFoundException("Il faut sélectionner au moins 2 prestations.", 400);
            }

            // Recherche de la boîte par ID
            $box = Box::find($values['id']);
            if (!$box) {
                throw new ModelNotFoundException();
            }

            // Mise à jour des propriétés de la boîte
            $box->libelle = filter_var($values['libelle'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $box->description = filter_var($values['description'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $box->kdo = filter_var($values['kdo'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $box->message_kdo = filter_var($values['message_kdo'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $box->token = filter_var($values['token'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $box->updated_at = date('Y-m-d H:i:s');

            // Détacher les anciennes prestations
            $box->prestation()->detach();

            // Attacher les nouvelles prestations et calculer le montant total
            foreach ($values['prestations'] as $prestationId) {
                $prestation = Prestation::find($prestationId);
                if ($prestation) {
                    $categories[] = $prestation->cat_id;
                    $box->prestation()->attach($prestationId, ['quantite' => 1]);
                    $montant += $prestation->tarif;
                }
            }

            // Vérification que les prestations proviennent de catégories différentes
            if (count(array_unique($categories)) < 2) {
                $box->prestation()->detach();
                throw new CoffretServiceNotFoundException("Il faut sélectionner des prestations de catégories différentes.", 400);
            }

            // Mettre à jour le montant total et sauvegarder les modifications
            $box->montant = $montant;
            $box->save();
        } catch (ModelNotFoundException $e) {
            throw new CoffretServiceNotFoundException("La boîte spécifiée n'a pas été trouvée.", 404);
        } catch (QueryException $e) {
            throw new CoffretServiceNotFoundException("Erreur de base de données.", 500);
        }
    }


}    














