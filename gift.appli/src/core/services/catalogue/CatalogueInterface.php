<?php

namespace gift\appli\core\services\catalogue;

/**
 * Interface CatalogueInterface
 * Définit les méthodes nécessaires pour gérer le catalogue.
 */
interface CatalogueInterface
{
    /**
     * Récupère toutes les catégories.
     *
     * @return array Tableau des catégories
     * @throws CatalogueServiceNotFoundException En cas d'erreur interne
     */
    public function getCategories(): array;

    /**
     * Récupère toutes les prestations.
     *
     * @return array Tableau des prestations
     * @throws CatalogueServiceNotFoundException En cas d'erreur interne
     */
    public function getPrestations(): array;

    /**
     * Récupère les coffrets prédéfinis.
     *
     * @return array Tableau des coffrets prédéfinis
     * @throws CatalogueServiceNotFoundException En cas d'erreur interne
     */
    public function getBoxesPredefined(): array;

    /**
     * Récupère les prestations avec leur catégorie.
     *
     * @return array Tableau des prestations avec leur catégorie
     * @throws CatalogueServiceNotFoundException En cas d'erreur interne
     */
    public function getPrestationsWithCategorie(): array;

    /**
     * Convertit un entier en couleur CSS.
     *
     * @param int $number Nombre entier pour la génération de couleur
     * @return string Code couleur CSS
     */
    public function intToCssColor(int $number): string;

    /**
     * Récupère une catégorie par son identifiant.
     *
     * @param int $id Identifiant de la catégorie
     * @return array Détails de la catégorie
     * @throws CatalogueServiceNotFoundException Si la catégorie n'existe pas
     */
    public function getCategorieById(int $id): array;

    /**
     * Récupère une prestation par son identifiant.
     *
     * @param string $id Identifiant de la prestation
     * @return array Détails de la prestation
     * @throws CatalogueServiceNotFoundException En cas d'erreur interne
     */
    public function getPrestationById(string $id): array;

    /**
     * Récupère les prestations d'une catégorie spécifique.
     *
     * @param int $categ_id Identifiant de la catégorie
     * @return array Tableau des prestations de la catégorie
     * @throws CatalogueServiceNotFoundException En cas d'erreur interne
     */
    public function getPrestationsByCategorie(int $categ_id): array;

    /**
     * Crée une nouvelle catégorie avec les valeurs fournies.
     *
     * @param array $values Valeurs de la catégorie (libelle, description, csrf)
     * @throws CatalogueServiceInvalidDataException Si les données ne sont pas valides
     * @throws CatalogueServiceNoDataFoundException Si aucune donnée trouvée pour la sauvegarde
     */
    public function createCategorie(array $values): void;

    /**
     * Trie les prestations d'une catégorie spécifique par tarif.
     *
     * @param int $categ_id Identifiant de la catégorie
     * @param string $order Ordre de tri (ascendant ou descendant)
     * @return array Tableau des prestations triées par tarif
     * @throws CatalogueServiceNotFoundException En cas d'erreur interne
     */
    public function sortPrestationByTarif(int $categ_id, string $order = 'asc'): array;
}
