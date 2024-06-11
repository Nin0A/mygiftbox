<?php

namespace gift\appli\core\services\auth;

interface AuthInterface
{
    /**
     * Login a user with given values.
     *
     * @param array $values
     * @throws \Exception
     */
    public function login(array $values);

    /**
     * Register a new user with given values.
     *
     * @param array $values
     * @throws \Exception
     */
    public function register(array $values);

    /**
     * Check if a user already exists by email.
     *
     * @param string $email
     * @return bool
     */
    public function userAlreadyExist(string $email): bool;

    /**
     * Logout the current user.
     */
    public function logout();

    /**
     * Check if a user is authenticated.
     *
     * @return bool
     */
    public static function isAuthenticate(): bool;
}
