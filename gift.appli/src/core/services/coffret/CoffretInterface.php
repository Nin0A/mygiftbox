<?php

namespace gift\appli\core\services\coffret;

interface CoffretInterface
{
    /**
     * Récupère tous les coffrets.
     *
     * @return array Tableau des coffrets
     * @throws CoffretServiceNotFoundException En cas d'erreur interne
     */
    public function getBoxes(): array;

    /**
     * Crée un nouveau coffret avec les valeurs fournies.
     *
     * @param array $values Valeurs du coffret (libelle, description, montant, kdo, message_kdo, token, csrf, prestations)
     * @return void
     * @throws CoffretServiceNotFoundException En cas d'erreur interne
     * @throws CoffretServiceNotEnoughDataException Si les données ne sont pas suffisantes
     */
    public function createBox(array $values): void;

    /**
     * Récupère les coffrets créés par un utilisateur spécifique.
     *
     * @param string $email Email de l'utilisateur
     * @return array Tableau des coffrets de l'utilisateur
     * @throws CoffretServiceNotFoundException En cas d'erreur interne
     * @throws CoffretServiceNotEnoughDataException Si l'email n'est pas fourni
     */
    public function getBoxesByUser(string $email): array;

    /**
     * Récupère les détails d'un coffret par son identifiant.
     *
     * @param string $id Identifiant du coffret
     * @return array Détails du coffret
     * @throws CoffretServiceNotFoundException Si le coffret n'existe pas
     */
    public function getBoxById(string $id): array;

    /**
     * Récupère les identifiants des prestations associées à un coffret spécifique.
     *
     * @param string $id Identifiant du coffret
     * @return array Tableau des identifiants des prestations associées
     * @throws CoffretServiceNotFoundException En cas d'erreur interne
     */
    public function getPrestationsByBoxId(string $id); 

    /**
     * Modifie un coffret existant avec les valeurs fournies.
     *
     * @param array $values Valeurs du coffret à modifier (id, libelle, description, montant, kdo, message_kdo, token, csrf, prestations)
     * @return void
     * @throws CoffretServiceNotFoundException Si le coffret n'est pas trouvé
     * @throws CoffretServiceNotEnoughDataException Si les données ne sont pas suffisantes
     */
    public function modifiedBox(array $values): void;
}
