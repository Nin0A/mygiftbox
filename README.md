# 🌟 mygiftbox 🌟
## Nino Arcelin, Lina Terras, Dimitri Walczak-Vela-Mena

##Détails des TDs

### TD1 : Rappels sur l'ORM Eloquent
| **Nino**                                          | **Dimitri**                             | **Lina** |
|:-:                                                |:-:                                      |:-:|
| Écriture du code                                  | /                                       | / |
| Documentation                                     | Documentation                           | / |
| /                                                 | Réalisation diagramme                   | / |

### TD2 : Débuter avec Slim
| **Nino**                                          | **Dimitri**                             | **Lina** |
|:-:                                                |:-:                                      |:-:|
| Écriture du code                                  | Écriture du code                        | / |
| Documentation                                     | /                                       | / |
| Ajustement des routes & fichier httpd.conf        | /                                       | / |

### TD3 : routes, actions, modèles
| **Nino**                                          | **Dimitri**                             | **Lina** |
|:-:                                                |:-:                                      |:-:|
| Écriture du code                                  | Écriture du code                        | / |
| Documentation                                     | /                                       | / |
|        /                                          | /                                       | / |

### TD5 : architecture app-noyau-infrastructure
| **Nino**                                          | **Dimitri**                             | **Lina** |
|:-:                                                |:-:                                      |:-:|
| Écriture du code                                  | Écriture du code                        | / |
| Documentation                                     | /                                       | / |
| Création de la nouvelle structure du projet       | /                                       | / |

### TD 6 : projet giftbox
| **Nino**                                          | **Dimitri**                             | **Lina** |
|:-:                                                |:-:                                      |:-:|
| Écriture du diagramme de classe                   | Écriture du code                        | / |
| Documentation                                     | /                                       | / |
|                | /                                       | / |

### TD 7 : formulaires, authentification
| **Nino**                                          | **Dimitri**                             | **Lina** |
|:-:                                                |:-:                                      |:-:|
| Mise en place des tokens CSRF                     | Écriture du code                        | / |
| Documentation                                     | /                                       | / |
| Programmation des divers formulaires              | /                                       | / |

### TD 8 : construire une api json
| **Nino**                                          | **Dimitri**                             | **Lina** |
|:-:                                                |:-:                                      |:-:|
| /                                                 | Écriture du code                        | / |
| /                                                 | /                                       | / |
| /                                                 | /                                       | / |

## Fonctionnalités détaillées

| Fonctionnalités                                          | Développeur                         | État |
|:-                                                       |:-:                                  |:-:|
| 1.Afficher la liste des prestations                        | Nino                                | Fonctionnel ✅ |
| 2.Afficher le détail d'une prestation                      | Nino                                | Fonctionnel ✅ |
| 3.Liste de prestations par catégories                      | Nino                                | Fonctionnel ✅ |
| 4.Liste de catégories                                      | Nino                                | Fonctionnel ✅ |
| 5.Tri par prix ⭐️                                             | Dimitri                             | Fonctionnel ✅ |
| 6.Création d'un coffret vide                               | Nino                                | Fonctionnel ✅ |
| 7.Ajout de prestations dans le coffret                     | Nino                                | Fonctionnel ✅ |
| 8.Affichage d'un coffret                                   | Nino                                | Fonctionnel ✅ |
| 9.Validation d'un coffret                                  | Nino/Dimitri                        | Fonctionnel ✅ |
| 10.Paiement d'un coffret                                    | Dimitri                             | Fonctionnel ✅ |
| 11.Modification d'un coffret : suppression de prestations   | Nino                                | Fonctionnel ✅ |
| 12.Modification d'un coffret : modification des quantités⭐️   | /                                   | Non réalisée ❌ |
| 13.Génération de l'URL d'accès                              | Dimitri                             | Fonctionnel ✅ |
| 14.Accès au coffret                                         | Nino                                | Fonctionnel ✅ |
| 15.Signin                                                   | Nino                                | Fonctionnel ✅ |
| 16.Register                                                 | Nino                                | Fonctionnel ✅ |
| 17.Accéder à ses coffrets                                   | Nino                                | Fonctionnel ✅ |
| 18.Afficher les box prédéfinies                             | Nino                                | Fonctionnel ✅ |
| 19.Créer un coffret prérempli à partir d'une box            | /                                   | Non réalisée ❌ |
| 20.Créer un coffret prérempli à partir d'une box affichée⭐️   | /                                   | Non réalisée ❌ |
| 21.API : liste des prestations                              | Dimitri                             | Fonctionnel ✅ |
| 22.API : liste des catégories                               | Dimitri                             | Fonctionnel ✅ |
| 23.API : liste des prestations d'une catégorie              | Dimitri                             | Fonctionnel ✅ |
| 24.API : accès à un coffret                                 | Dimitri                             | Fonctionnel ✅ |

⭐️ = BONUS

Voici une version plus propre de vos instructions d'installation :

## Installation du projet

Pour installer ce projet, suivez les étapes ci-dessous :

1. **Cloner le projet :**

    ```bash
    git clone git@github.com:Nin0A/mygiftbox.git
    ```

2. **Ajouter un fichier `.env` à la racine du projet avec les champs suivants :**

    ```plaintext
    MYSQL_ROOT_PASSWORD=root
    MYSQL_DATABASE=mygiftbox
    MYSQL_USER=root
    MYSQL_PASSWORD=root
    ```

3. **Ajouter un fichier `.htaccess` dans `gift.appli/public/` :**

    ```apache
    RewriteEngine On

    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [QSA,L]
    ```

4. **Lancer `docker-compose.yml` qui se trouve à la racine du projet :**

    ```bash
    docker compose up -d
    ```

5. **Accéder à Adminer sur le port **10127** du localhost, créer une table `mygiftbox` et insérer le jeu de données qui se trouve dans `init.sql`.**

Le projet est maintenant fonctionnel et accessible. Accès au projet **http://localhost:10025**

## Remarques générales :

Nous avons développé toutes les fonctionnalités obligatoires de l'application, mais nous n'avons pas pu implémenter toutes celles en bonus en raison de contraintes de temps et de ressources. Lina n'a apporté aucune contribution au projet, comme le montre le résumé des TDs. Il se peut également que quelques exceptions ne soient pas gérées correctement. Cependant, notre programme est commenté, respecte l'architecture MVC. Ce projet est également disponible sur Docketu ici : http://docketu.iutnc.univ-lorraine.fr:10025.



