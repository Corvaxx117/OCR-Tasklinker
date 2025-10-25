# Tasklinker

Gestion simplifiée de projets et de tâches avec un tableau Kanban (TODO / DOING / DONE), sélection multiple de membres, archivage de projets, et suppression asynchrone via JavaScript. Projet pédagogique construit sur Symfony 7.3.

## Objectif

Fournir une base claire pour:

-   Créer des projets, y associer des membres (employés)
-   Créer et gérer des tâches liées à un projet
-   Visualiser les tâches sur un board par statut
-   Archiver un projet (au lieu de le supprimer définitivement)
-   Supprimer tâches / employés côté client sans rechargement

## Stack & Prérequis

-   PHP >= 8.2
-   Composer
-   Base de données MySQL/MariaDB (ou compatible Doctrine)
-   Symfony CLI (recommandé): https://symfony.com/download

## Bundles & Extensions principaux

| Domaine                  | Packages                                                                                   |
| ------------------------ | ------------------------------------------------------------------------------------------ |
| Framework                | symfony/framework-bundle, symfony/runtime                                                  |
| Console & outils         | symfony/console, symfony/dotenv, symfony/yaml                                              |
| ORM & BDD                | doctrine/orm, doctrine/dbal, doctrine/doctrine-bundle, doctrine/doctrine-migrations-bundle |
| Formulaires & Validation | symfony/form, symfony/validator                                                            |
| Templating               | symfony/twig-bundle, twig/twig, twig/extra-bundle                                          |
| Assets                   | symfony/asset                                                                              |
| Logs                     | symfony/monolog-bundle                                                                     |
| Dev                      | symfony/maker-bundle                                                                       |

## 🗂 Architecture

```
src/
	Controller/ (actions invocables par domaine: Project, Task, Team)
	Entity/ (Project, Task, Employee + Enums)
	Enum/ (TaskStatus, ContractType, AccessStatus)
	Form/ (ProjectType, TaskType, EmployeeType)
public/
	css/style.css
	js/DeleteProject.js, DeleteTask.js, DeleteEmployee.js
	js/class/MultiSelectEnhancer.js, DeleteLinkHandler.js
templates/
	base.html.twig + sous-dossiers project/, task/, team/
migrations/ (évolution des schémas)
```

## Entités & Enums

### Project

-   name, description, startedAt, deadline
-   members: ManyToMany Employee
-   tasks: OneToMany Task
-   archivage des pojets

### Task

-   title, description, deadline, status (enum TaskStatus)
-   project: ManyToOne Project
-   assignee aux employés

### Employee

-   firstName, lastName, email, contractType (enum ContractType), accessStatus (enum AccessStatus)

### TaskStatus (enum)

```
TODO | DOING | DONE
```

Utilisé pour construire le board Kanban (`templates/task/board.html.twig`)

## Fonctionnalités Implémentées

-   Création / édition de projet (édition simplifiée: titre + membres)
-   Sélection multiple de membres avec amélioration JS (Select2 si dispo, sinon fallback compteur)
-   CRUD tâches + affichage détaillé + board Kanban trié par `TaskStatus`
-   Suppression asynchrone (fetch) pour projets (archivage), tâches, employés
-   Archivage projet au lieu de suppression directe (lien JS redirige vers route archive)
-   Enums Doctrine pour garantir cohérence des statuts
-   Migrations versionnées pour évolution incrémentale (statuts, suppression tags, etc.)

## Validation & Sécurité (côté serveur)

Les formulaires reposent sur les contraintes Validator définies au niveau des entités. Le HTML5 peut marquer certains champs requis, mais la source d'autorité reste Symfony Validator.

## Installation

```bash
git clone https://github.com/Corvaxx117/OCR-Tasklinker tasklinker
cd tasklinker
composer install

# Copier le fichier d'environnement
cp .env .env.local

# Ajuster DATABASE_URL dans .env.local (exemple)
# DATABASE_URL="mysql://user:pass@127.0.0.1:3306/tasklinker?charset=utf8mb4"

# Créer base + exécuter migrations
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate -n

```

## Lancer le serveur

```bash
symfony serve --no-tls
# ou
php -S 127.0.0.1:8000 -t public
```

Accès: http://127.0.0.1:8000

## Flux d'utilisation

1. Créer des employés (team)
2. Créer un projet et sélectionner des membres (multi-select JS)
3. Créer des tâches rattachées au projet
4. Consulter le board pour suivre l'avancement (colonne TODO / DOING / DONE)
5. Archiver un projet via icône corbeille (suppression logique) et retirer tâches/employés au besoin

## Front-End / JS

### Suppressions asynchrones

Chaque type (projet, tâche, employé) a son fichier dédié: `DeleteProject.js`, `DeleteTask.js`, `DeleteEmployee.js` s'appuyant sur la classe générique `DeleteLinkHandler.js`.

Principe:

-   Clic sur lien `<a class="delete-xxx" data-action="/route">`
-   Confirmation
-   `fetch` avec méthode (DELETE ou POST selon route)
-   Suppression du nœud dans le DOM en cas de succè

Tasklinker est une application Symfony 7.3 de gestion de projets orientée pédagogie, illustrant: relations Doctrine, enums, migrations incrémentales, formulaires avancés, progressive enhancement JS et séparation des responsabilités front/back.
