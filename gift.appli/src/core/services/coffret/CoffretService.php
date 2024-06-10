<?php


namespace gift\appli\core\services\coffret;
use gift\appli\core\domain\entities\Prestation;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use \gift\appli\app\utils\CsrfService;
use \gift\appli\core\domain\entities\Box;
use Illuminate\Database\QueryException;

use \gift\appli\core\services\coffret\CoffretServiceNotFoundException;




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
    
            $montant=0;

            // Création de la nouvelle boîte
            $newBox = new Box();
            $newBox->libelle = filter_var($values['libelle'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $newBox->description = filter_var($values['description'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $newBox->kdo = filter_var($values['kdo'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $newBox->message_kdo = filter_var($values['message_kdo'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $newBox->token = filter_var($values['token'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $newBox->created_at = date('Y-m-d H:i:s');
            $newBox->updated_at = date('Y-m-d H:i:s');

            $newBox->save();

            foreach ($values['prestations'] as $prestationId) {
                $newBox->prestation()->attach($prestationId, ['quantite' => 1]);
                $montant += Prestation::where('id', '=', $prestationId)->value('tarif');
            }

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

            // Recherche de la boîte par ID
            $box = Box::find($values['id']);

            // Mise à jour des propriétés de la boîte
            $box->libelle = filter_var($values['libelle'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $box->description = filter_var($values['description'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $box->kdo = filter_var($values['kdo'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $box->message_kdo = filter_var($values['message_kdo'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $box->token = filter_var($values['token'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $box->updated_at = date('Y-m-d H:i:s');

            // Mettre à jour les prestations et recalculer le montant
            $montant = 0;
            $box->prestation()->detach();

            foreach ($values['prestations'] as $prestationId) {
                $box->prestation()->attach($prestationId, ['quantite' => 1]);
                $montant += Prestation::where('id', '=', $prestationId)->value('tarif');
            }

            $box->montant = $montant;

            // Sauvegarder les modifications
            $box->save();
        } catch (ModelNotFoundException $e) {
            throw new CoffretServiceNotFoundException("La boîte spécifiée n'a pas été trouvée.", 404);
        } catch (QueryException $e) {
            throw new CoffretServiceNotFoundException("Erreur de base de données.", 500);
        }
    }

}    














