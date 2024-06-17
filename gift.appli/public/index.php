<?php
// Activer le mode strict pour les types de PHP, obligeant les types à être strictement respectés
declare(strict_types=1);

// Inclure le fichier autoload généré par Composer pour charger automatiquement les classes
require_once __DIR__ . '/../src/vendor/autoload.php'; /* vendor -> autoload */

// Inclure le fichier de bootstrap de l'application pour initialiser les configurations nécessaires
$app = require_once __DIR__ . '/../src/infrastructure/conf/bootstrap.php'; /* application bootstrap */

// Exécuter l'application
$app->run();
