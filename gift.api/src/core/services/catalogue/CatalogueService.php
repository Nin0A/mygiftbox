<?php


namespace gift\api\core\services\catalogue;

use gift\api\core\services\catalogue\CatalogueInterface;
use gift\api\core\domain\entities\Categorie;
use gift\api\core\domain\entities\Prestation;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use \gift\api\app\utils\CsrfService;
use Illuminate\Database\QueryException;


class CatalogueService implements CatalogueInterface
{
    /**
     * 
     */
    public function getCategories(): array
    {
        try {

            $categories = Categorie::all();

            if (!$categories) throw new ModelNotFoundException();

            return $categories->toArray();

        } catch (ModelNotFoundException $e) {
            throw new CatalogueServiceNotFoundException("Erreur interne", 500);
        }

       
    }

    public function getPrestations(): array
    {
        try {

            $prestations = Prestation::all();

            if (!$prestations) throw new ModelNotFoundException();

            return $prestations->toArray();

        } catch (ModelNotFoundException $e) {
            throw new CatalogueServiceNotFoundException("Erreur interne", 500);
        }

       
    }

    /**
     * 
     */
    public function getCategorieById(int $id): array
    {
        try {

            $categorie = Categorie::find($id);

            if (!$categorie) throw new ModelNotFoundException();

            return $categorie->toArray();
            
        } catch (ModelNotFoundException $e) {
            throw new CatalogueServiceNotFoundException("Erreur interne", 500);
        }
        
    }

    /**
     * 
     */
    public function getPrestationById(string $id): array
    {
        try {

            $prestation = Prestation::find($id);

            if (!$prestation) throw new ModelNotFoundException();

            return $prestation->toArray();

        } catch (ModelNotFoundException $e) {
            throw new CatalogueServiceNotFoundException("Erreur interne", 500);
        }
        
    }

    /**
     * 
     */
    public function getPrestationsByCategorie(int $categ_id): array
    {
        try {

            $prestations = Prestation::where('cat_id', '=', $categ_id)->get();

            if (!$prestations) throw new ModelNotFoundException();

            return $prestations->toArray();
            
        } catch (ModelNotFoundException $e) {
            throw new CatalogueServiceNotFoundException("Erreur interne", 500);
        }

    }

    public function createCategorie(array $values): void
{
    try {
        // Vérification du token CSRF
        CsrfService::check($values['csrf']);
        
        // Validation des données
        if ($values['libelle'] !== filter_var($values['libelle'], FILTER_SANITIZE_FULL_SPECIAL_CHARS) ||
            $values['description'] !== filter_var($values['description'], FILTER_SANITIZE_FULL_SPECIAL_CHARS)) {
            throw new CatalogueServiceInvalidDataException("Les données ne sont pas valides");
        }

        // Création de la nouvelle catégorie
        $newCategory = new Categorie();
        $newCategory->libelle = filter_var($values['libelle'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $newCategory->description = filter_var($values['description'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $newCategory->save();
    } catch (QueryException $e) {
        throw new CatalogueServiceNoDataFoundException("Erreur de sauvegarde", 500);
    }
}
    public function modificatePrestation(array $properties): void
    {
        $prestationId = $properties['id'];
        unset($properties['id']);

        $prestation = Prestation::find($prestationId);

        $prestation->fill($properties);

        $prestation->save();
    }

    public function definirOuModifierCategoriePrestation(int $prestationId, int $categorieId): void
    {

        $prestation = Prestation::find($prestationId);

        $prestation->categorie_id = $categorieId;

        $prestation->save();
    }


    public function sortPrestationByTarif(int $categ_id, string $order = 'asc'): array
    {
        try {
            $prestations = Prestation::where('cat_id', '=', $categ_id)->orderBy('tarif', $order)->get();

            if (!$prestations) throw new ModelNotFoundException();

            return $prestations->toArray();

        } catch (ModelNotFoundException $e) {
            throw new CatalogueServiceNotFoundException("Erreur interne", 500);
        }
    }

}
