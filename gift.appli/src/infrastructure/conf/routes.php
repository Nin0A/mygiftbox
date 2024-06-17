<?php
declare(strict_types=1);

// Importation des classes d'action
use gift\appli\app\actions\GetCategorieIdAction;
use gift\appli\app\actions\GetErrorAction;
use gift\appli\app\actions\GetLoginAction;
use gift\appli\app\actions\GetLogoutAction;
use gift\appli\app\actions\PostLoginAction;
use gift\appli\app\actions\PostBoxCreateAction;
use gift\appli\app\actions\GetBoxCreateAction;
use gift\appli\app\actions\PostCategorieCreateAction;
use gift\appli\app\actions\GetCategorieCreateAction;
use gift\appli\app\actions\GetDefaultAction;
use gift\appli\app\actions\GetPrestationAction;
use gift\appli\app\actions\GetBoxModifiedAction;
use gift\appli\app\actions\PostBoxModifiedAction;
use gift\appli\app\actions\GetRegisterAction;
use gift\appli\app\actions\PostRegisterAction;
use gift\appli\app\actions\GetBuyAction;
use gift\appli\app\actions\PostBuyAction;
use gift\appli\app\actions\ShowBoxAction;
use gift\appli\app\actions\GetBoxPredefinedAction;

// Importation de la classe Eloquent pour l'initialisation de la base de données
use gift\appli\infrastructure\utils\Eloquent;

/* Initialisation de la base de donnée */
Eloquent::init(__DIR__ . '/gift.db.conf.ini.dist');

// Configuration des routes Slim
return function (\Slim\App $app): \Slim\App {

    // Route par défaut
    $app->get('/', GetDefaultAction::class);

    /**
     * GET /categories/{id}
     * Route pour récupérer les détails d'une catégorie par son ID
     */
    $app->get('/categories/{id}', GetCategorieIdAction::class)->setName('categorie_details');

    /**
     * GET /categories
     * Route pour récupérer la liste des catégories
     */
    $app->get('/categories', GetCategorieIdAction::class)->setName('categories_list');

    /**
     * GET /prestation
     * Route pour récupérer la liste des prestations
     */
    $app->get('/prestation', GetPrestationAction::class)->setName('prestation');

    /**
     * GET /box/create
     * Affiche le formulaire de création de boîte
     */
    $app->get('/box/create', GetBoxCreateAction::class)->setName('box_creation');

    /**
     * GET /box/predefined
     * Affiche les boîtes prédéfinies
     */
    $app->get('/box/predefined', GetBoxPredefinedAction::class)->setName('box_predefined');

    /**
     * POST /box/create
     * Traite la création d'une boîte via le formulaire
     */
    $app->post('/box/create', PostBoxCreateAction::class);

    /**
     * GET /box/modified/{id}
     * Affiche le formulaire de modification d'une boîte
     */
    $app->get('/box/modified/{id}', GetBoxModifiedAction::class)->setName('box_modified');

    /**
     * POST /box/modified/{id}
     * Traite la modification d'une boîte via le formulaire
     */
    $app->post('/box/modified/{id}', PostBoxModifiedAction::class)->setName('post_box_modified');

    /**
     * GET /categorie/create
     * Affiche le formulaire de création de catégorie
     */
    $app->get('/categorie/create', GetCategorieCreateAction::class)->setName('categorie_creation');

    /**
     * POST /categorie/create
     * Traite la création d'une catégorie via le formulaire
     */
    $app->post('/categorie/create', PostCategorieCreateAction::class);

    /**
     * GET /account/login
     * Affiche le formulaire de connexion
     */
    $app->get('/account/login', GetLoginAction::class)->setName('account_login');

    /**
     * POST /account/login
     * Traite la connexion via le formulaire
     */
    $app->post('/account/login', PostLoginAction::class);

    /**
     * GET /account/register
     * Affiche le formulaire d'inscription
     */
    $app->get('/account/register', GetRegisterAction::class)->setName('account_register');

    /**
     * GET /account/register/{error_message}
     * Affiche le formulaire d'inscription avec un message d'erreur spécifique
     */
    $app->get('/account/register/{error_message}', GetRegisterAction::class);

    /**
     * POST /account/register
     * Traite l'inscription via le formulaire
     */
    $app->post('/account/register', PostRegisterAction::class);

    /**
     * GET /account/logout
     * Déconnecte l'utilisateur
     */
    $app->get('/account/logout', GetLogoutAction::class)->setName('account_logout');

    /**
     * GET /error
     * Affiche une page d'erreur
     */
    $app->get('/error', GetErrorAction::class)->setName('error');

    $app->get('/payment', GetBuyAction::class)->setName('get_payment');

    $app->get('/payment/{id}', GetBuyAction::class)->setName('get_paymentBox');

    $app->post('/payment/{id}', PostBuyAction::class)->setName('post_payment');

    $app->get('/paid/{token}', ShowBoxAction::class)->setName('paid_success');

    return $app;
};
