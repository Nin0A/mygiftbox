<?php

// Déclarer le namespace pour cette classe
namespace gift\appli\app\actions;

// Importer les interfaces ResponseInterface et ServerRequestInterface du PSR-7
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// Déclaration d'une classe abstraite AbstractAction
abstract class AbstractAction {
    // Méthode abstraite __invoke qui doit être implémentée par toute classe qui hérite de AbstractAction
    // Elle prend une requête, une réponse et un tableau d'arguments et retourne une réponse
    public abstract function __invoke(Request $request, Response $response, array $args): Response;
}
