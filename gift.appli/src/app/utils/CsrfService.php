<?php

namespace gift\appli\app\utils;

class CsrfService
{
    const CSRF_TOKEN_KEY = 'csrf_token';

    /**
     * Génère un token CSRF, le stocke en session et le retourne.
     *
     * @return string Le token CSRF généré
     */
    public static function generate()
    {
        // Génération du token CSRF
        $token = bin2hex(random_bytes(32));

        // Stockage du token en session
        $_SESSION[self::CSRF_TOKEN_KEY] = $token;

        return $token;
    }

    /**
     * Vérifie si le token CSRF reçu correspond à celui stocké en session.
     * Si la vérification échoue, une exception est levée.
     * Le token en session est supprimé après la vérification.
     *
     * @param string $token Le token CSRF reçu à vérifier
     * @throws \Exception Si la vérification échoue (token invalide ou absent)
     */
    public static function check($token)
    {
        // Vérification de la présence du token en session
        if (!isset($_SESSION[self::CSRF_TOKEN_KEY])) {
            throw new \Exception('Token CSRF absent en session');
        }

        // Récupération du token en session
        $storedToken = $_SESSION[self::CSRF_TOKEN_KEY];

        // Suppression du token en session après récupération
        unset($_SESSION[self::CSRF_TOKEN_KEY]);

        // Vérification du token reçu avec celui en session
        if (!hash_equals($storedToken, $token)) {
            throw new \Exception('Échec de vérification du token CSRF');
        }
    }
}
