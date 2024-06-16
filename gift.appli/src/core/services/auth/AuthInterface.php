<?php

namespace gift\appli\core\services\auth;

/**
 * Interface AuthInterface
 *
 * Cette interface définit les méthodes nécessaires pour gérer l'authentification des utilisateurs.
 */
interface AuthInterface
{
    /**
     * Connecter un utilisateur avec les valeurs fournies.
     *
     * @param array $values Les valeurs de connexion (email, password, csrf).
     * @throws \Exception En cas d'erreur lors de la connexion.
     */
    public function login(array $values);

    /**
     * Inscrire un nouvel utilisateur avec les valeurs fournies.
     *
     * @param array $values Les valeurs d'inscription (email, password1, password2, csrf).
     * @throws \Exception En cas d'erreur lors de l'inscription.
     */
    public function register(array $values);

    /**
     * Vérifier si un utilisateur existe déjà par email.
     *
     * @param string $email L'email de l'utilisateur à vérifier.
     * @return bool True si l'utilisateur existe, False sinon.
     */
    public function userAlreadyExist(string $email): bool;

    /**
     * Déconnecter l'utilisateur actuel.
     */
    public function logout();

    /**
     * Vérifier si un utilisateur est authentifié.
     *
     * @return bool True si l'utilisateur est authentifié, False sinon.
     */
    public static function isAuthenticate(): bool;
}
