# Loisirs pour les étudiants : site d'inscription en ligne

Projet réalisé dans le cours de développement web à l'Université du Québec à Trois-Rivières (automne 2025).

Le site permet aux étudiants de consulter les activités de loisirs proposées (natation, kayak, échecs, etc.) et de s'y inscrire. Un espace d'administration protégé par un mot de passe sert à ajouter ou supprimer des activités et à suivre le nombre de places restantes.

Une vidéo de démonstration se trouve dans `Vidéo Descriptive.zip`.

## Fonctionnalités

**Côté étudiant**
- Liste des activités avec leur responsable et le nombre d'inscrits (`index.php`)
- Formulaire d'inscription ; seules les activités qui ont encore des places sont proposées (`inscription.php`)
- Carte pour localiser l'UQTR (`carte.php`)

**Côté administration**
- Connexion avec session PHP (`login.php`, `log_out.php`)
- Tableau de bord : places restantes et taux de remplissage de chaque activité, calculés en SQL (`admin.php`)
- Ajout d'une activité (`add_activity.php`) et suppression d'une activité avec ses inscriptions

## Technologies

- PHP 8 avec PDO et requêtes préparées
- MySQL / MariaDB
- HTML et CSS
- Environnement local : WampServer

## Base de données

Trois tables, créées par `database.sql` :

| Table | Rôle |
|---|---|
| `activity` | activités proposées, responsable et nombre maximal de places |
| `inscription` | inscriptions des étudiants (nom, date de naissance, activité, motivation) |
| `users` | comptes d'administration, mots de passe hachés avec `password_hash` |

Le tableau de bord repose sur une seule requête : une jointure externe entre `activity` et `inscription`, regroupée par activité, qui calcule le nombre d'inscrits, les places restantes et le pourcentage de places libres.

## Lancer le projet en local

1. Installer WampServer et démarrer Apache et MySQL.
2. Copier le dossier du projet dans le dossier `www` de WampServer.
3. Dans phpMyAdmin, créer une base nommée `database` et y importer `database.sql`.
4. Adapter au besoin les paramètres de connexion dans `db_connect.php` (par défaut : utilisateur `root`, sans mot de passe).
5. Ouvrir `http://localhost/projet-site-web/`.

Compte d'administration de démonstration : `admin` / `admin123`. Il ne sert qu'aux tests en local.

## Limites connues

- Les inscriptions sont reliées aux activités par leur nom et non par une clé étrangère. Deux activités portant le même nom partagent donc leurs inscrits.
- Il n'y a pas de protection CSRF sur les formulaires d'administration.
- La validation des champs du formulaire d'inscription reste minimale.

## Auteur

Jordan Kouayep Mbakop, étudiant au baccalauréat en informatique, UQTR.
