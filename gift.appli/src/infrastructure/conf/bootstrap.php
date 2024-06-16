<?php

use \Slim\Factory\AppFactory;

// Crée une nouvelle instance d'application Slim
$app = AppFactory::create();

// Ajoute le middleware de routage
$app->addRoutingMiddleware();

// Ajoute le middleware d'erreurs
$app->addErrorMiddleware(true, false, false);

/* $app->setBasePath('/projets/mygiftbox/gift.appli/public'); */

// Inclus les routes dans l'application Slim
$app = (require_once __DIR__ . '/routes.php')($app);

// Configuration de Twig pour les vues
$twig = \Slim\Views\Twig::create(
    '../src/app/views/', // Chemin vers le répertoire des vues Twig
    [
        //'cache' => './src/app/views/cache', // Optionnel : cache des vues (commenté pour le moment)
        'auto_reload' => true, // Rechargement automatique des vues en mode développement
        'strict_variables' => true // Activation des variables strictes
    ]
);

// Ajoute le middleware Twig à l'application Slim
$app->add(\Slim\Views\TwigMiddleware::create($app, $twig));

// Ajoute des variables globales à toutes les vues Twig
$twig->getEnvironment()
    ->addGlobal('globals', [
        'img_dir' => '/img' // Ajoute une variable globale 'img_dir'
    ]);

// Démarre la session PHP
session_start();

// Retourne l'application Slim configurée
return $app;
