<?php


namespace gift\appli\core\services\catalogue;

use gift\appli\core\services\catalogue\CatalogueInterface;
use gift\appli\core\domain\entities\Categorie;
use gift\appli\core\domain\entities\Prestation;
use Illuminate\Database\Eloquent\ModelNotFoundException;


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

        $newCategoryId = Categorie::create($values)->id;

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
