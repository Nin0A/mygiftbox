<?php

namespace gift\appli\core\services\coffret;

use gift\appli\core\domain\entities\Prestation;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use gift\appli\app\utils\CsrfService;
use gift\appli\core\domain\entities\Box;
use gift\appli\core\domain\entities\User;
use Illuminate\Database\QueryException;

use gift\appli\core\services\coffret\CoffretServiceNotFoundException;
use gift\appli\core\services\coffret\CoffretServiceNotEnoughDataException;

/**
 * Service pour la gestion des coffrets cadeaux.
 */
class CoffretService implements CoffretInterface
{
    /**
     * Récupère tous les coffrets, triés par date de mise à jour décroissante.
     *
     * @return array Tableau des coffrets
     * @throws CoffretServiceNotFoundException En cas d'erreur interne
     */
    public function getBoxes(): array
    {
        try {
            // Récupère tous les coffrets, triés par date de mise à jour décroissante
            $boxes = Box::all()->sortByDesc('updated_at');

            if (!$boxes)
                throw new ModelNotFoundException();

            return $boxes->toArray();

        } catch (ModelNotFoundException $e) {
            throw new CoffretServiceNotFoundException("Internal Error");
        }
    }

    /**
     * Récupère tous les coffrets créés par un utilisateur spécifique.
     *
     * @param string $email Email de l'utilisateur
     * @return array Tableau des coffrets créés par l'utilisateur
     * @throws CoffretServiceNotFoundException En cas d'erreur interne
     * @throws CoffretServiceNotEnoughDataException Si l'utilisateur n'est pas authentifié
     */
    public function getBoxesByUser(string $email): array
    {
        try {
            // Vérifie que l'email est défini
            if (!isset($email))
                throw new CoffretServiceNotEnoughDataException('Unauthenticated user');

            // Récupère l'ID de l'utilisateur par son email
            $user = User::where('user_id', '=', $email)->first()->id;

            // Récupère les coffrets créés par cet utilisateur
            $boxes = Box::where('createur_id', '=', $user)->get();

            if (!$boxes)
                throw new ModelNotFoundException();

            return $boxes->toArray();

        } catch (ModelNotFoundException $e) {
            throw new CoffretServiceNotFoundException("Non-existent box");
        } catch (CoffretServiceNotEnoughDataException $e) {
            throw new CoffretServiceNotEnoughDataException($e->getMessage());
        }
    }

    /**
     * Crée un nouveau coffret avec les valeurs fournies.
     *
     * @param array $values Valeurs du coffret (libelle, description, kdo, message_kdo, token, prestations, csrf)
     * @throws CoffretServiceNotEnoughDataException Si les données fournies ne sont pas suffisantes
     * @throws CoffretServiceNotFoundException En cas d'erreur interne
     */
    public function createBox(array $values): void
    {
        try {
            // Vérification du token CSRF
            CsrfService::check($values['csrf']);

            $montant = 0;
            $categories = [];

            // Vérification que le nombre de prestations sélectionnées est d'au moins 2
            if (count($values['prestations']) < 2) {
                throw new CoffretServiceNotEnoughDataException("You must select at least 2 services");
            }

            // Création du nouveau coffret
            $newBox = new Box();
            $newBox->libelle = filter_var($values['libelle'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $newBox->description = filter_var($values['description'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $newBox->kdo = filter_var($values['kdo'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $newBox->message_kdo = filter_var($values['message_kdo'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $newBox->token = filter_var($values['token'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $newBox->created_at = date('Y-m-d H:i:s');
            $newBox->updated_at = date('Y-m-d H:i:s');
            $newBox->createur_id = User::select('id')->where('user_id', '=', $_SESSION['USER'])->first()->id;

            // Sauvegarde initiale du coffret
            $newBox->save();

            // Attache les prestations sélectionnées et calcule le montant total
            foreach ($values['prestations'] as $prestationId) {
                $prestation = Prestation::find($prestationId);
                if ($prestation) {
                    $categories[] = $prestation->cat_id;
                    $newBox->prestation()->attach($prestationId, ['quantite' => 1]);
                    $montant += $prestation->tarif;
                }
            }

            // Vérification que les prestations proviennent de catégories différentes
            if (count(array_unique($categories)) < 2) {
                $newBox->prestation()->detach();
                $newBox->delete();
                throw new CoffretServiceNotFoundException("You must select services from different categories.");
            }

            // Met à jour le montant total et sauvegarde les modifications
            $newBox->montant = $montant;
            $newBox->save();
        } catch (QueryException $e) {
            throw new CoffretServiceNotFoundException("Backup error");
        }
    }

    /**
     * Récupère les détails d'un coffret par son identifiant.
     *
     * @param string $id Identifiant du coffret
     * @return array Détails du coffret
     * @throws CoffretServiceNotFoundException Si le coffret n'existe pas
     */
    public function getBoxById(string $id): array
    {
        try {
            // Récupère le coffret par son ID
            $box = Box::find($id);

            if (!$box)
                throw new CoffretServiceNotFoundException("Non-existent box");

            return $box->toArray();

        } catch (CoffretServiceNotFoundException $e) {
            throw $e;
        } catch (ModelNotFoundException $e) {
            throw new CoffretServiceNotFoundException("Internal Error");
        }
    }

    /**
     * Récupère les prestations d'un coffret spécifique par son identifiant.
     *
     * @param string $id Identifiant du coffret
     * @return array Tableau des identifiants des prestations
     * @throws CoffretServiceNotFoundException En cas d'erreur interne
     */
    public function getPrestationsByBoxId(string $id)
    {
        try {
            $res = [];

            // Récupère le coffret par son ID
            $box = Box::find($id);

            if (!$box)
                throw new CoffretServiceNotFoundException("Non-existent box");

            // Récupère les prestations associées au coffret
            $prestations = $box->prestation()->get();

            // Ajoute les identifiants des prestations à un tableau
            foreach ($prestations as $p)
                array_push($res, $p->id);

            return $res;

        } catch (ModelNotFoundException $e) {
            throw new CoffretServiceNotFoundException("Internal Error");
        }
    }

    /**
     * Modifie un coffret existant avec les nouvelles valeurs fournies.
     *
     * @param array $values Valeurs du coffret à modifier (id, libelle, description, kdo, message_kdo, token, prestations, csrf)
     * @throws CoffretServiceNotEnoughDataException Si les données fournies ne sont pas suffisantes
     * @throws CoffretServiceNotFoundException Si le coffret spécifié n'est pas trouvé
     * @throws CoffretServiceNotFoundException En cas d'erreur de base de données
     */
    public function modifiedBox(array $values): void
    {
        try {
            // Vérification du token CSRF
            CsrfService::check($values['csrf']);

            $montant = 0;
            $categories = [];

            // Vérification que le nombre de prestations sélectionnées est d'au moins 2
            if (count($values['prestations']) < 2) {
                throw new CoffretServiceNotEnoughDataException("You must select at least 2 services.");
            }

            // Recherche du coffret par ID
            $box = Box::find($values['id']);
            if (!$box) {
                throw new ModelNotFoundException();
            }

            // Mise à jour des propriétés du coffret
            $box->libelle = filter_var($values['libelle'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $box->description = filter_var($values['description'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $box->kdo = filter_var($values['kdo'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $box->message_kdo = filter_var($values['message_kdo'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $box->token = filter_var($values['token'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            // token, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $box->updated_at = date('Y-m-d H:i:s');

            // Détachement des anciennes prestations
            $box->prestation()->detach();

            // Attache des nouvelles prestations et calcul du montant total
            foreach ($values['prestations'] as $prestationId) {
                $prestation = Prestation::find($prestationId);
                if ($prestation) {
                    $categories[] = $prestation->cat_id;
                    $box->prestation()->attach($prestationId, ['quantite' => 1]);
                    $montant += $prestation->tarif;
                }
            }

            // Vérification que les prestations proviennent de catégories différentes
            if (count(array_unique($categories)) < 2) {
                $box->prestation()->detach();
                throw new CoffretServiceNotFoundException("You must select services from different categories.");
            }

            // Mettre à jour le montant total et sauvegarder les modifications
            $box->montant = $montant;
            $box->save();
        } catch (ModelNotFoundException $e) {
            throw new CoffretServiceNotFoundException("The specified box was not found.");
        } catch (QueryException $e) {
            throw new CoffretServiceNotFoundException("Database error.");
        }
    }

    /**
     * Met à jour le statut de la boîte spécifiée par son ID.
     *
     * @param string $id ID de la boîte à mettre à jour
     * @param int $newStatus Nouveau statut de la boîte
     * @throws CoffretServiceNotFoundException Si la boîte spécifiée n'est pas trouvée
     */
    public function updateBoxStatus(string $id, int $newStatus): void
    {
        try {
            // Recherche de la boîte par ID
            $box = Box::find($id);
            if (!$box) {
                throw new ModelNotFoundException();
            }

            // Mise à jour du statut de la boîte
            $box->statut = $newStatus;
            $box->save();
        } catch (ModelNotFoundException $e) {
            throw new CoffretServiceNotFoundException("La boîte spécifiée n'a pas été trouvée.", 404);
        } catch (QueryException $e) {
            throw new CoffretServiceNotFoundException("Erreur de base de données lors de la mise à jour du statut de la boîte.", 500);
        }
    }

    public function getBoxByToken(string $token): ?Box {
        try {
            $box = Box::where('token', $token)->firstOrFail();
            return $box;
        } catch (ModelNotFoundException $e) {
            return null;
        }
    }


}    














