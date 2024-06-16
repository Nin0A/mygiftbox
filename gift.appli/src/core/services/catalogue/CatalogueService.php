<?php

namespace gift\appli\core\services\catalogue;

use gift\appli\core\services\catalogue\CatalogueInterface;
use gift\appli\core\domain\entities\Categorie;
use gift\appli\core\domain\entities\Prestation;
use gift\appli\core\domain\entities\Box;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use \gift\appli\app\utils\CsrfService;
use Illuminate\Database\QueryException;

class CatalogueService implements CatalogueInterface
{
    /**
     * Récupère toutes les catégories.
     *
     * @return array Tableau des catégories
     * @throws CatalogueServiceNotFoundException En cas d'erreur interne
     */
    public function getCategories(): array
    {
        try {
            $categories = Categorie::all();

            if (!$categories) {
                throw new ModelNotFoundException();
            }

            return $categories->toArray();
        } catch (ModelNotFoundException $e) {
            throw new CatalogueServiceNotFoundException("Internal Error");
        }
    }

    /**
     * Récupère toutes les prestations.
     *
     * @return array Tableau des prestations
     * @throws CatalogueServiceNotFoundException En cas d'erreur interne
     */
    public function getPrestations(): array
    {
        try {
            $prestations = Prestation::all();

            if (!$prestations) {
                throw new ModelNotFoundException();
            }

            return $prestations->toArray();
        } catch (ModelNotFoundException $e) {
            throw new CatalogueServiceNotFoundException("Internal Error ");
        }
    }

    /**
     * Récupère les coffrets prédéfinis.
     *
     * @return array Tableau des coffrets prédéfinis
     * @throws CatalogueServiceNotFoundException En cas d'erreur interne
     */
    public function getBoxesPredefined(): array
    {
        try {
            $boxes_predefined = Box::where('createur_id', '=', NULL)->get();

            return $boxes_predefined->toArray();
        } catch (ModelNotFoundException $e) {
            throw new CatalogueServiceNotFoundException("Internal Error");
        }
    }

    /**
     * Récupère les prestations avec leur catégorie.
     *
     * @return array Tableau des prestations avec leur catégorie
     * @throws CatalogueServiceNotFoundException En cas d'erreur interne
     */
    public function getPrestationsWithCategorie(): array
    {
        try {
            $result = [];

            $categories = Categorie::all();

            foreach ($categories as $categorie) {
                $prestations = Prestation::where('cat_id', '=', $categorie->id)->get();

                foreach ($prestations as $prestation) {
                    $result[] = [
                        'presta' => $prestation->toArray(),
                        'color_id' => $this->intToCssColor($categorie->id)
                    ];
                }
            }

            return $result;
        } catch (ModelNotFoundException $e) {
            throw new CatalogueServiceNotFoundException("Internal Error");
        }
    }

    /**
     * Convertit un entier en couleur CSS.
     *
     * @param int $number Nombre entier pour la génération de couleur
     * @return string Code couleur CSS
     */
    public function intToCssColor(int $number): string
    {
        // Génère des couleurs en créant des intervalles larges distincts pour les composantes RVB
        $r = ($number * 37) % 256;
        $g = ($number * 67) % 256;
        $b = ($number * 97) % 256;

        // Assure que les couleurs sont pastel en augmentant leur luminosité et en réduisant la saturation
        $r = $r + 0.75 * (255 - $r);
        $g = $g + 0.75 * (255 - $g);
        $b = $b + 0.75 * (255 - $b);

        // Convertit en code couleur hexadécimal
        $colorCode = sprintf('%02x%02x%02x', $r, $g, $b);

        // Retourne le code couleur CSS
        return '#' . $colorCode;
    }

    /**
     * Récupère une catégorie par son identifiant.
     *
     * @param int $id Identifiant de la catégorie
     * @return array Détails de la catégorie
     * @throws CatalogueServiceNotFoundException Si la catégorie n'existe pas
     */
    public function getCategorieById(int $id): array
    {
        try {
            $categorie = Categorie::findOrFail($id);

            return $categorie->toArray();
        } catch (ModelNotFoundException $e) {
            throw new CatalogueServiceNotFoundException("Category does not exist");
        }
    }

    /**
     * Récupère une prestation par son identifiant.
     *
     * @param string $id Identifiant de la prestation
     * @return array Détails de la prestation
     * @throws CatalogueServiceNotFoundException En cas d'erreur interne
     */
    public function getPrestationById(string $id): array
    {
        try {
            $prestation = Prestation::findOrFail($id);

            return $prestation->toArray();
        } catch (ModelNotFoundException $e) {
            throw new CatalogueServiceNotFoundException("Internal Error");
        }
    }

    /**
     * Récupère les prestations d'une catégorie spécifique.
     *
     * @param int $categ_id Identifiant de la catégorie
     * @return array Tableau des prestations de la catégorie
     * @throws CatalogueServiceNotFoundException En cas d'erreur interne
     */
    public function getPrestationsByCategorie(int $categ_id): array
    {
        try {
            $prestations = Prestation::where('cat_id', '=', $categ_id)->get();

            if (!$prestations) {
                throw new ModelNotFoundException();
            }

            return $prestations->toArray();
        } catch (ModelNotFoundException $e) {
            throw new CatalogueServiceNotFoundException("Internal Error");
        }
    }

    /**
     * Crée une nouvelle catégorie avec les valeurs fournies.
     *
     * @param array $values Valeurs de la catégorie (libelle, description, csrf)
     * @throws CatalogueServiceInvalidDataException Si les données ne sont pas valides
     * @throws CatalogueServiceNoDataFoundException Si aucune donnée trouvée pour la sauvegarde
     */
    public function createCategorie(array $values): void
    {
        try {
            // Vérification du token CSRF
            CsrfService::check($values['csrf']);

            // Validation des données
            if ($values['libelle'] !== filter_var($values['libelle'], FILTER_SANITIZE_FULL_SPECIAL_CHARS) ||
                $values['description'] !== filter_var($values['description'], FILTER_SANITIZE_FULL_SPECIAL_CHARS)) {
                throw new CatalogueServiceInvalidDataException("Data is invalid");
            }

            // Création de la nouvelle catégorie
            $newCategory = new Categorie();
            $newCategory->libelle = filter_var($values['libelle'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $newCategory->description = filter_var($values['description'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $newCategory->save();
        } catch (QueryException $e) {
            throw new CatalogueServiceNoDataFoundException("Backup error", 500);
        }
    }

    /**
     * Trie les prestations d'une catégorie spécifique par tarif.
     *
     * @param int $categ_id Identifiant de la catégorie
     * @param string $order Ordre de tri (ascendant ou descendant)
     * @return array Tableau des prestations triées par tarif
     * @throws CatalogueServiceNotFoundException En cas d'erreur interne
     */
    public function sortPrestationByTarif(int $categ_id, string $order = 'asc'): array
    {
        try {
            $prestations = Prestation::where('cat_id', '=', $categ_id)->orderBy('tarif', $order)->get();

            if (!$prestations) {
                throw new ModelNotFoundException();
            }

            return $prestations->toArray();
        } catch (
            ModelNotFoundException $e) {
                throw new CatalogueServiceNotFoundException("Internal Error");
            }
        }
    
    }
    